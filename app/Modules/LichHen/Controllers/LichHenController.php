<?php

namespace App\Modules\LichHen\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\LichHen\Services\LichHenService;
use App\Modules\LichHen\Repositories\LichHenRepositoryInterface;
use App\Modules\BenhNhan\Repositories\BenhNhanRepositoryInterface;
use App\Modules\BacSi\Repositories\BacSiRepositoryInterface;
use App\Traits\TraVeDuLieuTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class LichHenController extends Controller
{
    use TraVeDuLieuTrait;

    protected LichHenService $lichHenService;
    protected LichHenRepositoryInterface $lichHenRepo;
    protected BenhNhanRepositoryInterface $benhNhanRepo;
    protected BacSiRepositoryInterface $bacSiRepo;

    public function __construct(
        LichHenService $lichHenService,
        LichHenRepositoryInterface $lichHenRepo,
        BenhNhanRepositoryInterface $benhNhanRepo,
        BacSiRepositoryInterface $bacSiRepo
    ) {
        $this->lichHenService = $lichHenService;
        $this->lichHenRepo = $lichHenRepo;
        $this->benhNhanRepo = $benhNhanRepo;
        $this->bacSiRepo = $bacSiRepo;
    }

    public function danhSach(Request $request): JsonResponse
    {
        $boLoc = $request->only(['trang_thai', 'moc_thoi_gian', 'ngay_kham', 'tu_ngay', 'den_ngay', 'tu_khoa', 'bac_si_id', 'benh_nhan_id']);
        $danhSach = $this->lichHenRepo->layDanhSachCoLoc($boLoc);
        return $this->thanhCongResponse($danhSach, 'Danh sách lịch hẹn');
    }

    public function datLich(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'bac_si_id' => 'required|exists:bac_si,id',
            'ngay_kham' => 'required|date|after_or_equal:today',
            'gio_kham' => 'required|string',
            'trieu_chung' => 'nullable|string',
            'so_cccd' => 'nullable|string|max:20',
            'ho_ten' => 'nullable|string|max:150',
            'so_dien_thoai' => 'nullable|string|max:20',
            'tien_su_benh' => 'nullable|string',
            'tien_su_di_ung' => 'nullable|string',
            'nguoi_lien_he_khan_cap' => 'nullable|string|max:150',
            'sdt_khan_cap' => 'nullable|string|max:20',
            'nhom_mau' => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            return $this->thatBaiResponse('Thông tin không hợp lệ', 422, $validator->errors());
        }

        try {
            $user = $request->user('sanctum') ?? $request->user();
            $lichHen = $this->lichHenService->datLichHen($request->all(), $user);
            return $this->thanhCongResponse($lichHen, 'Đặt lịch khám thành công', 201);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return $this->thatBaiResponse($ve->getMessage(), 422, $ve->errors());
        } catch (\Exception $e) {
            return $this->thatBaiResponse($e->getMessage(), 400);
        }
    }

    public function lichSuCuaToi(Request $request): JsonResponse
    {
        $user = $request->user();
        $benhNhan = $this->benhNhanRepo->timTheoTaiKhoanId($user->id);

        if (!$benhNhan) {
            return $this->thanhCongResponse([], 'Chưa có lịch sử khám bệnh');
        }

        $boLoc = $request->only(['trang_thai', 'moc_thoi_gian', 'ngay_kham', 'tu_ngay', 'den_ngay', 'tu_khoa']);
        $boLoc['benh_nhan_id'] = $benhNhan->id;

        $danhSach = $this->lichHenRepo->layDanhSachCoLoc($boLoc);
        return $this->thanhCongResponse($danhSach, 'Lịch sử khám bệnh của bạn');
    }

    public function lichKhamBacSi(Request $request): JsonResponse
    {
        $user = $request->user();
        $bacSi = $this->bacSiRepo->timTheoTaiKhoanId($user->id);

        if (!$bacSi) {
            return $this->thatBaiResponse('Tài khoản không gắn với bác sĩ nào', 403);
        }

        $boLoc = $request->only(['trang_thai', 'moc_thoi_gian', 'ngay_kham', 'tu_ngay', 'den_ngay', 'tu_khoa']);
        $boLoc['bac_si_id'] = $bacSi->id;

        $danhSach = $this->lichHenRepo->layDanhSachCoLoc($boLoc);
        return $this->thanhCongResponse($danhSach, 'Danh sách lịch khám của bác sĩ');
    }

    public function xacNhan(Request $request, int $id): JsonResponse
    {
        $user = $request->user('sanctum') ?? $request->user();
        try {
            $lichHen = $this->lichHenService->xacNhanLichHen($id, $user);
            return $this->thanhCongResponse($lichHen, 'Đã xác nhận lịch hẹn');
        } catch (\Exception $e) {
            return $this->thatBaiResponse($e->getMessage(), 403);
        }
    }

    public function batDau(Request $request, int $id): JsonResponse
    {
        $user = $request->user('sanctum') ?? $request->user();
        try {
            $lichHen = $this->lichHenService->batDauKham($id, $user);
            return $this->thanhCongResponse($lichHen, 'Đã bắt đầu buổi khám');
        } catch (\Exception $e) {
            return $this->thatBaiResponse($e->getMessage(), 403);
        }
    }

    public function hoanThanh(Request $request, int $id): JsonResponse
    {
        $user = $request->user('sanctum') ?? $request->user();
        try {
            $ketQua = $this->lichHenService->hoanThanhKham($id, $request->all(), $user);
            return $this->thanhCongResponse($ketQua, 'Hoàn thành khám và đã tạo hóa đơn thanh toán');
        } catch (\Exception $e) {
            return $this->thatBaiResponse($e->getMessage(), 400);
        }
    }

    public function huy(Request $request, int $id): JsonResponse
    {
        $lyDo = $request->input('ly_do', 'Người bệnh hoặc phòng khám yêu cầu hủy');
        $user = $request->user('sanctum') ?? $request->user();
        try {
            $lichHen = $this->lichHenService->huyLichHen($id, $lyDo, $user);
            return $this->thanhCongResponse($lichHen, 'Đã hủy lịch hẹn thành công');
        } catch (\Exception $e) {
            return $this->thatBaiResponse($e->getMessage(), 400);
        }
    }
}
