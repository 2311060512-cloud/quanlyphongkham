<?php

namespace App\Modules\TaiKhoan\Services;

use App\Modules\TaiKhoan\Repositories\TaiKhoanRepositoryInterface;
use App\Modules\BenhNhan\Repositories\BenhNhanRepositoryInterface;
use App\Modules\TaiKhoan\Models\VaiTro;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class XacThucService
{
    protected TaiKhoanRepositoryInterface $taiKhoanRepo;
    protected BenhNhanRepositoryInterface $benhNhanRepo;

    public function __construct(
        TaiKhoanRepositoryInterface $taiKhoanRepo,
        BenhNhanRepositoryInterface $benhNhanRepo
    ) {
        $this->taiKhoanRepo = $taiKhoanRepo;
        $this->benhNhanRepo = $benhNhanRepo;
    }

    public function dangNhap(string $tenDangNhap, string $matKhau): array
    {
        $taiKhoan = $this->taiKhoanRepo->timTheoTenDangNhap($tenDangNhap);

        if (!$taiKhoan || !Hash::check($matKhau, $taiKhoan->mat_khau)) {
            throw ValidationException::withMessages([
                'tai_khoan' => ['Tên đăng nhập hoặc mật khẩu không chính xác.'],
            ]);
        }

        if ($taiKhoan->trang_thai !== 'HOAT_DONG') {
            throw ValidationException::withMessages([
                'tai_khoan' => ['Tài khoản này đã bị khóa hoặc ngừng kích hoạt.'],
            ]);
        }

        $token = $taiKhoan->createToken('clinic_auth_token')->plainTextToken;
        $benhNhan = $this->benhNhanRepo->timTheoTaiKhoanId($taiKhoan->id);

        return [
            'token' => $token,
            'tai_khoan' => [
                'id' => $taiKhoan->id,
                'ten_dang_nhap' => $taiKhoan->ten_dang_nhap,
                'ho_ten' => $taiKhoan->ho_ten,
                'email' => $taiKhoan->email,
                'so_dien_thoai' => $taiKhoan->so_dien_thoai ?? $benhNhan?->so_dien_thoai,
                'so_cccd' => $benhNhan?->so_cccd,
                'tien_su_benh' => $benhNhan?->tien_su_benh,
                'tien_su_di_ung' => $benhNhan?->tien_su_di_ung,
                'nguoi_lien_he_khan_cap' => $benhNhan?->nguoi_lien_he_khan_cap,
                'sdt_khan_cap' => $benhNhan?->sdt_khan_cap,
                'nhom_mau' => $benhNhan?->nhom_mau,
                'vai_tro' => $taiKhoan->vaiTro?->ma_vai_tro ?? 'BENH_NHAN',
                'ten_vai_tro' => $taiKhoan->vaiTro?->ten_vai_tro ?? 'Bệnh nhân',
            ],
        ];
    }

    public function dangKy(array $duLieu): array
    {
        $vaiTro = VaiTro::where('ma_vai_tro', 'BENH_NHAN')->first();

        $taiKhoan = $this->taiKhoanRepo->taoMoi([
            'ten_dang_nhap' => $duLieu['ten_dang_nhap'],
            'email' => $duLieu['email'],
            'mat_khau' => Hash::make($duLieu['mat_khau']),
            'ho_ten' => $duLieu['ho_ten'],
            'so_dien_thoai' => $duLieu['so_dien_thoai'] ?? null,
            'vai_tro_id' => $vaiTro ? $vaiTro->id : 3,
            'trang_thai' => 'HOAT_DONG',
        ]);

        $maBenhNhan = 'BN' . date('Ymd') . str_pad($taiKhoan->id, 4, '0', STR_PAD_LEFT);
        $this->benhNhanRepo->taoMoi([
            'tai_khoan_id' => $taiKhoan->id,
            'ma_benh_nhan' => $maBenhNhan,
            'ho_ten' => $taiKhoan->ho_ten,
            'so_dien_thoai' => $taiKhoan->so_dien_thoai,
            'gioi_tinh' => $duLieu['gioi_tinh'] ?? 'KHAC',
            'ngay_sinh' => $duLieu['ngay_sinh'] ?? null,
            'dia_chi' => $duLieu['dia_chi'] ?? null,
        ]);

        $token = $taiKhoan->createToken('clinic_auth_token')->plainTextToken;

        return [
            'token' => $token,
            'tai_khoan' => [
                'id' => $taiKhoan->id,
                'ten_dang_nhap' => $taiKhoan->ten_dang_nhap,
                'ho_ten' => $taiKhoan->ho_ten,
                'email' => $taiKhoan->email,
                'vai_tro' => 'BENH_NHAN',
                'ma_benh_nhan' => $maBenhNhan,
            ],
        ];
    }
}
