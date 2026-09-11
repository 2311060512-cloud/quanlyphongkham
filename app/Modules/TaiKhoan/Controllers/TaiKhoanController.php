<?php

namespace App\Modules\TaiKhoan\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\TaiKhoan\Services\TaiKhoanService;
use App\Modules\TaiKhoan\Models\VaiTro;
use App\Traits\TraVeDuLieuTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaiKhoanController extends Controller
{
    use TraVeDuLieuTrait;

    protected TaiKhoanService $taiKhoanService;

    public function __construct(TaiKhoanService $taiKhoanService)
    {
        $this->taiKhoanService = $taiKhoanService;
    }

    /**
     * API xem danh sách tài khoản kèm phân trang và lọc theo vai trò (Dành cho ADMIN)
     */
    public function danhSach(Request $request): JsonResponse
    {
        $soMoiTrang = (int) $request->query('per_page', 10);
        $vaiTroId = $request->query('vai_tro_id') ? (int) $request->query('vai_tro_id') : null;
        $tuKhoa = $request->query('tu_khoa');

        $danhSach = $this->taiKhoanService->danhSachTaiKhoan($soMoiTrang, $vaiTroId, $tuKhoa);
        return $this->thanhCongResponse($danhSach, 'Danh sách tài khoản hệ thống');
    }

    /**
     * API Khóa / Mở khóa tài khoản (HOAT_DONG <-> TAM_KHOA) (Dành cho ADMIN)
     */
    public function khoaMoKhoa(Request $request, int $id): JsonResponse
    {
        try {
            $currentUser = $request->user();
            $taiKhoan = $this->taiKhoanService->khoaMoKhoaTaiKhoan($id, $currentUser?->id);

            $thongBao = $taiKhoan->trang_thai === 'HOAT_DONG' 
                ? 'Mở khóa tài khoản thành công.' 
                : 'Đã khóa tài khoản thành công (trạng thái: KHOA).';

            return $this->thanhCongResponse($taiKhoan, $thongBao);
        } catch (\Exception $e) {
            return $this->thatBaiResponse($e->getMessage(), 400);
        }
    }

    /**
     * Lấy danh sách các vai trò
     */
    public function danhSachVaiTro(): JsonResponse
    {
        $vaiTro = VaiTro::all();
        return $this->thanhCongResponse($vaiTro, 'Danh sách vai trò người dùng');
    }
}
