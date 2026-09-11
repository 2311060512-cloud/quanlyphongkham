<?php

namespace App\Modules\BenhNhan\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\BenhNhan\Services\BenhNhanService;
use App\Traits\TraVeDuLieuTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BenhNhanController extends Controller
{
    use TraVeDuLieuTrait;

    protected BenhNhanService $benhNhanService;

    public function __construct(BenhNhanService $benhNhanService)
    {
        $this->benhNhanService = $benhNhanService;
    }

    public function danhSach(Request $request): JsonResponse
    {
        $soMoiTrang = $request->query('per_page', 15);
        $danhSach = $this->benhNhanService->danhSachBenhNhan((int)$soMoiTrang);
        return $this->thanhCongResponse($danhSach, 'Danh sách bệnh nhân');
    }

    public function chiTiet(int $id): JsonResponse
    {
        $benhNhan = $this->benhNhanService->chiTietBenhNhan($id);
        if (!$benhNhan) {
            return $this->thatBaiResponse('Không tìm thấy hồ sơ bệnh nhân', 404);
        }
        return $this->thanhCongResponse($benhNhan, 'Chi tiết hồ sơ bệnh nhân');
    }

    public function capNhat(Request $request, int $id): JsonResponse
    {
        $capNhat = $this->benhNhanService->capNhatHoSo($id, $request->all());
        if ($capNhat) {
            return $this->thanhCongResponse(null, 'Cập nhật thông tin bệnh nhân thành công');
        }
        return $this->thatBaiResponse('Không thể cập nhật thông tin', 400);
    }

    public function hoSoCuaToi(Request $request): JsonResponse
    {
        $user = $request->user();
        $hoSo = $this->benhNhanService->layHoSoTheoTaiKhoanId($user->id);
        if (!$hoSo) {
            return $this->thatBaiResponse('Chưa có hồ sơ bệnh nhân cho tài khoản này', 404);
        }
        return $this->thanhCongResponse($hoSo, 'Hồ sơ bệnh án điện tử của bạn');
    }

    public function capNhatHoSoCuaToi(Request $request): JsonResponse
    {
        $user = $request->user();
        $duLieu = $request->only([
            'ho_ten', 'so_dien_thoai', 'so_cccd', 'email', 'gioi_tinh',
            'ngay_sinh', 'dia_chi', 'nhom_mau', 'tien_su_benh',
            'tien_su_di_ung', 'nguoi_lien_he_khan_cap', 'sdt_khan_cap'
        ]);

        $capNhat = $this->benhNhanService->capNhatHoSoTheoTaiKhoanId($user->id, $duLieu);
        if ($capNhat) {
            $hoSo = $this->benhNhanService->layHoSoTheoTaiKhoanId($user->id);
            return $this->thanhCongResponse($hoSo, 'Cập nhật hồ sơ bệnh án thành công');
        }
        return $this->thatBaiResponse('Không thể cập nhật hồ sơ bệnh nhân', 400);
    }
}
