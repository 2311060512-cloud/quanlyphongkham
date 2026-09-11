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
        $user = $request->user();
        $truyVan = $this->lichHenRepo->getModel()->with(['benhNhan', 'bacSi.chuyenKhoa', 'hoaDon', 'suDungDichVu.dichVu']);

        if ($user) {
            $maVaiTro = $user->vaiTro ? strtoupper($user->vaiTro->ma_vai_tro) : 'BENH_NHAN';

            // Nếu là BỆNH NHÂN: chỉ xem lịch hẹn của chính mình
            if ($maVaiTro === 'BENH_NHAN') {
                $benhNhan = $this->benhNhanRepo->timTheoTaiKhoanId($user->id);
                if (!$benhNhan && !empty($user->so_dien_thoai)) {
                    $benhNhan = $this->benhNhanRepo->timTheoSoDienThoai($user->so_dien_thoai);
                    if ($benhNhan && !$benhNhan->tai_khoan_id) {
                        $benhNhan->update(['tai_khoan_id' => $user->id]);
                    }
                }
                if ($benhNhan) {
                    $truyVan->where('benh_nhan_id', $benhNhan->id);
                } else {
                    return $this->thanhCongResponse([], 'Chưa có lịch hẹn nào');
                }
            }
            // Nếu là BÁC SĨ: chỉ xem lịch hẹn thuộc bác sĩ phụ trách
            elseif ($maVaiTro === 'BAC_SI') {
                $bacSi = $this->bacSiRepo->timTheoTaiKhoanId($user->id);
                if ($bacSi) {
                    $truyVan->where('bac_si_id', $bacSi->id);
                } else {
                    return $this->thanhCongResponse([], 'Chưa có lịch hẹn nào');
                }
            }
            // ADMIN: xem toàn bộ danh sách lịch hẹn của phòng khám
        }

        $danhSach = $truyVan->latest()->get();
        return $this->thanhCongResponse($danhSach, 'Danh sách lịch hẹn');
    }

    public function datLich(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'bac_si_id' => 'required|exists:bac_si,id',
            'ngay_kham' => 'required|date|after_or_equal:today',
            'gio_kham' => 'required|string',
            'trieu_chung' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->thatBaiResponse('Thông tin không hợp lệ', 422, $validator->errors());
        }

        try {
            $lichHen = $this->lichHenService->datLichHen($request->all(), $request->user());
            return $this->thanhCongResponse($lichHen, 'Đặt lịch khám thành công', 201);
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

        $danhSach = $this->lichHenRepo->layTheoBenhNhan($benhNhan->id);
        return $this->thanhCongResponse($danhSach, 'Lịch sử khám bệnh của bạn');
    }

    public function lichKhamBacSi(Request $request): JsonResponse
    {
        $user = $request->user();
        $bacSi = $this->bacSiRepo->timTheoTaiKhoanId($user->id);

        if (!$bacSi) {
            return $this->thatBaiResponse('Tài khoản không gắn với bác sĩ nào', 403);
        }

        $ngayKham = $request->query('ngay_kham', date('Y-m-d'));
        $danhSach = $this->lichHenRepo->layTheoBacSi($bacSi->id, $ngayKham);

        return $this->thanhCongResponse($danhSach, "Danh sách lịch khám ngày $ngayKham");
    }

    public function xacNhan(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        if ($user && $user->vaiTro?->ma_vai_tro === 'BAC_SI') {
            $bacSi = $this->bacSiRepo->timTheoTaiKhoanId($user->id);
            $lichHen = $this->lichHenRepo->timTheoId($id);
            if (!$bacSi || !$lichHen || $lichHen->bac_si_id !== $bacSi->id) {
                return $this->thatBaiResponse('Bạn chỉ có thể xác nhận lịch khám của chính mình', 403);
            }
        }

        $this->lichHenService->xacNhanLichHen($id);
        return $this->thanhCongResponse(null, 'Đã xác nhận lịch hẹn');
    }

    public function batDau(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        if ($user && $user->vaiTro?->ma_vai_tro === 'BAC_SI') {
            $bacSi = $this->bacSiRepo->timTheoTaiKhoanId($user->id);
            $lichHen = $this->lichHenRepo->timTheoId($id);
            if (!$bacSi || !$lichHen || $lichHen->bac_si_id !== $bacSi->id) {
                return $this->thatBaiResponse('Bạn chỉ có thể khám bệnh nhân của chính mình', 403);
            }
        }

        $this->lichHenService->batDauKham($id);
        return $this->thanhCongResponse(null, 'Đã bắt đầu buổi khám');
    }

    public function hoanThanh(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        if ($user && $user->vaiTro?->ma_vai_tro === 'BAC_SI') {
            $bacSi = $this->bacSiRepo->timTheoTaiKhoanId($user->id);
            $lichHen = $this->lichHenRepo->timTheoId($id);
            if (!$bacSi || !$lichHen || $lichHen->bac_si_id !== $bacSi->id) {
                return $this->thatBaiResponse('Bạn chỉ có thể hoàn thành buổi khám của chính mình', 403);
            }
        }

        try {
            $ketQua = $this->lichHenService->hoanThanhKham($id, $request->all());
            return $this->thanhCongResponse($ketQua, 'Hoàn thành khám và đã tạo hóa đơn thanh toán');
        } catch (\Exception $e) {
            return $this->thatBaiResponse($e->getMessage(), 400);
        }
    }

    public function huy(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $lichHen = $this->lichHenRepo->timTheoId($id);
        if (!$lichHen) {
            return $this->thatBaiResponse('Không tìm thấy lịch hẹn', 404);
        }

        if ($user) {
            $maVaiTro = $user->vaiTro ? strtoupper($user->vaiTro->ma_vai_tro) : 'BENH_NHAN';
            if ($maVaiTro === 'BENH_NHAN') {
                $benhNhan = $this->benhNhanRepo->timTheoTaiKhoanId($user->id);
                if (!$benhNhan || $lichHen->benh_nhan_id !== $benhNhan->id) {
                    return $this->thatBaiResponse('Bạn không có quyền hủy lịch hẹn này', 403);
                }
                if ($lichHen->trang_thai !== 'CHO_XAC_NHAN') {
                    return $this->thatBaiResponse('Chỉ có thể hủy lịch hẹn khi đang ở trạng thái Chờ xác nhận', 400);
                }
            } elseif ($maVaiTro === 'BAC_SI') {
                $bacSi = $this->bacSiRepo->timTheoTaiKhoanId($user->id);
                if (!$bacSi || $lichHen->bac_si_id !== $bacSi->id) {
                    return $this->thatBaiResponse('Bạn không có quyền thao tác trên lịch hẹn của bác sĩ khác', 403);
                }
            }
        }

        $lyDo = $request->input('ly_do', 'Người bệnh hoặc phòng khám yêu cầu hủy');
        $this->lichHenService->huyLichHen($id, $lyDo);
        return $this->thanhCongResponse(null, 'Đã hủy lịch hẹn thành công');
    }
}
