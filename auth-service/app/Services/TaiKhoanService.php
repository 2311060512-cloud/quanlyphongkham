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

    public function taoMoi(array $duLieu): array
    {
        if (empty($duLieu['ten_dang_nhap']) || empty($duLieu['mat_khau'])) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'THIEU_THONG_TIN',
                'thong_diep' => 'Vui lòng cung cấp đầy đủ tên đăng nhập và mật khẩu.'
            ];
        }

        if ($this->taiKhoanRepo->timTheoTenDangNhapHoacEmail($duLieu['ten_dang_nhap'])) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'TEN_DANG_NHAP_DA_TON_TAI',
                'thong_diep' => 'Tên đăng nhập đã được sử dụng trong hệ thống.'
            ];
        }

        if (!empty($duLieu['email']) && $this->taiKhoanRepo->timTheoTenDangNhapHoacEmail($duLieu['email'])) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'EMAIL_DA_TON_TAI',
                'thong_diep' => 'Email đã được sử dụng trong hệ thống.'
            ];
        }

        $vaiTroId = $duLieu['vai_tro_id'] ?? null;
        if (!empty($duLieu['ma_vai_tro'])) {
            $vt = \App\Models\VaiTro::where('ma_vai_tro', strtoupper($duLieu['ma_vai_tro']))->first();
            if ($vt) $vaiTroId = $vt->id;
        }

        if (!$vaiTroId) {
            $vtMacDinh = \App\Models\VaiTro::where('ma_vai_tro', 'BENH_NHAN')->first();
            $vaiTroId = $vtMacDinh ? $vtMacDinh->id : 3;
        }

        $taiKhoan = $this->taiKhoanRepo->taoMoi([
            'ten_dang_nhap' => trim($duLieu['ten_dang_nhap']),
            'email' => !empty($duLieu['email']) ? trim($duLieu['email']) : (trim($duLieu['ten_dang_nhap']) . '@phongkham.vn'),
            'mat_khau' => \Illuminate\Support\Facades\Hash::make($duLieu['mat_khau']),
            'ho_ten' => !empty($duLieu['ho_ten']) ? trim($duLieu['ho_ten']) : trim($duLieu['ten_dang_nhap']),
            'so_dien_thoai' => $duLieu['so_dien_thoai'] ?? null,
            'vai_tro_id' => $vaiTroId,
            'trang_thai' => $duLieu['trang_thai'] ?? 'HOAT_DONG',
        ]);

        return [
            'thanh_cong' => true,
            'thong_diep' => 'Tạo tài khoản thành công.',
            'du_lieu' => $taiKhoan->load('vaiTro')
        ];
    }

    public function capNhatTrangThai(int $id, string $trangThai): array
    {
        $trangThai = strtoupper(trim($trangThai));
        if ($trangThai === 'KHOA') {
            $trangThai = 'BI_KHOA';
        }

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

        // Chặn tuyệt đối không cho phép khóa tài khoản ADMIN
        if ($taiKhoan->vaiTro && $taiKhoan->vaiTro->ma_vai_tro === 'ADMIN' && $trangThai === 'BI_KHOA') {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'KHONG_DUOC_KHOA_ADMIN',
                'thong_diep' => 'Không thể khóa tài khoản Quản trị viên (ADMIN) của hệ thống!'
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

    public function xoa(int $id): array
    {
        $taiKhoan = $this->taiKhoanRepo->timTheoId($id);
        if (!$taiKhoan) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'TAI_KHOAN_KHONG_TON_TAI',
                'thong_diep' => 'Không tìm thấy tài khoản cần xóa.'
            ];
        }

        // Chặn tuyệt đối không cho phép xóa tài khoản ADMIN
        if ($taiKhoan->vaiTro && $taiKhoan->vaiTro->ma_vai_tro === 'ADMIN') {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'KHONG_DUOC_XOA_ADMIN',
                'thong_diep' => 'Không thể xóa tài khoản Quản trị viên (ADMIN) của hệ thống!'
            ];
        }

        // Xóa tokens của tài khoản
        $taiKhoan->tokens()->delete();

        // Xóa hồ sơ bác sĩ liên kết nếu là tài khoản bác sĩ
        if ($taiKhoan->bacSi) {
            $taiKhoan->bacSi()->delete();
        }

        $this->taiKhoanRepo->xoa($id);

        return [
            'thanh_cong' => true,
            'thong_diep' => "Đã xóa tài khoản [{$taiKhoan->ten_dang_nhap}] thành công."
        ];
    }
}
