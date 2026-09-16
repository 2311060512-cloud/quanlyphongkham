<?php

namespace App\Services;

use App\Repositories\Contracts\TaiKhoanRepositoryInterface;

class TaiKhoanService
{
    protected TaiKhoanRepositoryInterface $taiKhoanRepo;

    public function __construct(TaiKhoanRepositoryInterface $taiKhoanRepo)
    {
        $this->taiKhoanRepo = $taiKhoanRepo;
    }

    public function danhSach(): array
    {
        $danhSach = $this->taiKhoanRepo->danhSach();
        return [
            'thanh_cong' => true,
            'thong_diep' => 'Lấy danh sách tài khoản thành công.',
            'du_lieu' => $danhSach
        ];
    }

    public function capNhatTrangThai(int $id, string $trangThai): array
    {
        $trangThaiHopLe = ['HOAT_DONG', 'BI_KHOA'];
        if (!in_array($trangThai, $trangThaiHopLe)) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'TRANG_THAI_KHONG_HOP_LE',
                'thong_diep' => 'Trạng thái phải là HOAT_DONG hoặc BI_KHOA.'
            ];
        }

        $taiKhoan = $this->taiKhoanRepo->timTheoId($id);
        if (!$taiKhoan) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'TAI_KHOAN_KHONG_TON_TAI',
                'thong_diep' => 'Không tìm thấy tài khoản để cập nhật.'
            ];
        }

        $this->taiKhoanRepo->doiTrangThai($id, $trangThai);

        return [
            'thanh_cong' => true,
            'thong_diep' => "Cập nhật trạng thái tài khoản thành {$trangThai} thành công.",
            'du_lieu' => $this->taiKhoanRepo->timTheoId($id)
        ];
    }

    public function doiMatKhau(int $id, string $matKhauMoi): array
    {
        $taiKhoan = $this->taiKhoanRepo->timTheoId($id);
        if (!$taiKhoan) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'TAI_KHOAN_KHONG_TON_TAI',
                'thong_diep' => 'Không tìm thấy tài khoản để đổi mật khẩu.'
            ];
        }

        $this->taiKhoanRepo->doiMatKhau($id, $matKhauMoi);

        return [
            'thanh_cong' => true,
            'thong_diep' => "Đổi mật khẩu cho tài khoản {$taiKhoan->ten_dang_nhap} thành công."
        ];
    }
}
