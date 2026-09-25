<?php

namespace App\Services;

use App\Models\BacSi;
use App\Models\TaiKhoan;
use App\Models\VaiTro;
use App\Repositories\Contracts\BacSiRepositoryInterface;
use App\Repositories\Contracts\TaiKhoanRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BacSiService
{
    protected BacSiRepositoryInterface $bacSiRepo;
    protected TaiKhoanRepositoryInterface $taiKhoanRepo;

    public function __construct(BacSiRepositoryInterface $bacSiRepo, TaiKhoanRepositoryInterface $taiKhoanRepo)
    {
        $this->bacSiRepo = $bacSiRepo;
        $this->taiKhoanRepo = $taiKhoanRepo;
    }

    public function danhSach(array $boLoc = []): array
    {
        if (isset($boLoc['ten']) && !isset($boLoc['tu_khoa'])) {
            $boLoc['tu_khoa'] = $boLoc['ten'];
        }

        $danhSach = $this->bacSiRepo->danhSach($boLoc);

        return [
            'thanh_cong' => true,
            'thong_diep' => 'Lấy danh sách bác sĩ thành công.',
            'tong_so' => $danhSach->count(),
            'du_lieu' => $danhSach
        ];
    }

    public function chiTiet(int $id): ?array
    {
        $bacSi = $this->bacSiRepo->timTheoId($id);
        if (!$bacSi) {
            return null;
        }

        return [
            'id' => $bacSi->id,
            'ma_bac_si' => $bacSi->ma_bac_si,
            'ho_ten' => $bacSi->ho_ten,
            'hoc_vi' => $bacSi->hoc_vi,
            'so_dien_thoai' => $bacSi->so_dien_thoai,
            'email' => $bacSi->email,
            'gia_kham' => (float)$bacSi->gia_kham,
            'phong_kham' => $bacSi->phong_kham,
            'kinh_nghiem' => $bacSi->kinh_nghiem,
            'trang_thai' => $bacSi->trang_thai,
            'chuyen_khoa' => $bacSi->chuyenKhoa ? [
                'id' => $bacSi->chuyenKhoa->id,
                'ma_khoa' => $bacSi->chuyenKhoa->ma_khoa,
                'ten_khoa' => $bacSi->chuyenKhoa->ten_khoa,
            ] : null,
            'tai_khoan' => $bacSi->taiKhoan ? [
                'id' => $bacSi->taiKhoan->id,
                'ten_dang_nhap' => $bacSi->taiKhoan->ten_dang_nhap,
                'email' => $bacSi->taiKhoan->email,
            ] : null,
        ];
    }

    public function themMoi(array $duLieu): array
    {
        return DB::transaction(function () use ($duLieu) {
            $vaiTroBacSi = VaiTro::where('ma_vai_tro', 'BAC_SI')->first();
            if (!$vaiTroBacSi) {
                $vaiTroBacSi = VaiTro::create([
                    'ma_vai_tro' => 'BAC_SI',
                    'ten_vai_tro' => 'Bác sĩ khám bệnh'
                ]);
            }

            $tenDangNhap = $duLieu['ten_dang_nhap'] ?? ('bs_' . strtolower(Str::random(6)));
            $email = $duLieu['email'] ?? ($tenDangNhap . '@phongkham.vn');
            $matKhau = $duLieu['mat_khau'] ?? '123456';

            // 1. Tao ban ghi tai khoan dang nhap
            $taiKhoan = $this->taiKhoanRepo->taoMoi([
                'vai_tro_id' => $vaiTroBacSi->id,
                'ten_dang_nhap' => $tenDangNhap,
                'email' => $email,
                'ho_ten' => $duLieu['ho_ten'],
                'so_dien_thoai' => $duLieu['so_dien_thoai'] ?? null,
                'mat_khau' => Hash::make($matKhau),
                'trang_thai' => 'HOAT_DONG',
            ]);

            // 2. Tao ban ghi bac si lien ket sang bang bac_si
            $maBacSi = $duLieu['ma_bac_si'] ?? ('BS' . str_pad((string)$taiKhoan->id, 4, '0', STR_PAD_LEFT));

            $bacSi = $this->bacSiRepo->taoMoi([
                'tai_khoan_id' => $taiKhoan->id,
                'chuyen_khoa_id' => $duLieu['chuyen_khoa_id'],
                'ma_bac_si' => $maBacSi,
                'ho_ten' => $duLieu['ho_ten'],
                'hoc_vi' => $duLieu['hoc_vi'] ?? null,
                'so_dien_thoai' => $duLieu['so_dien_thoai'] ?? null,
                'email' => $email,
                'gia_kham' => $duLieu['gia_kham'] ?? 200000.00,
                'phong_kham' => $duLieu['phong_kham'] ?? null,
                'kinh_nghiem' => $duLieu['kinh_nghiem'] ?? ($duLieu['so_nam_kinh_nghiem'] ?? null),
                'trang_thai' => $duLieu['trang_thai'] ?? 'DANG_LAM_VIEC',
            ]);

            return [
                'thanh_cong' => true,
                'thong_diep' => 'Thêm mới bác sĩ và tạo tài khoản thành công.',
                'du_lieu' => $bacSi->load(['chuyenKhoa', 'taiKhoan']),
                'tai_khoan_tam' => [
                    'ten_dang_nhap' => $tenDangNhap,
                    'mat_khau' => $matKhau,
                ]
            ];
        });
    }

    public function capNhat(int $id, array $duLieu): array
    {
        $bacSi = $this->bacSiRepo->timTheoId($id);
        if (!$bacSi) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'BAC_SI_KHONG_TON_TAI',
                'thong_diep' => 'Không tìm thấy bác sĩ cần cập nhật.'
            ];
        }

        $duLieuCapNhat = [];
        $cacTruongChoPhep = ['chuyen_khoa_id', 'ho_ten', 'hoc_vi', 'so_dien_thoai', 'email', 'gia_kham', 'phong_kham', 'kinh_nghiem', 'trang_thai'];

        foreach ($cacTruongChoPhep as $truong) {
            if (isset($duLieu[$truong])) {
                $duLieuCapNhat[$truong] = $duLieu[$truong];
            }
        }

        if (isset($duLieu['so_nam_kinh_nghiem']) && !isset($duLieuCapNhat['kinh_nghiem'])) {
            $duLieuCapNhat['kinh_nghiem'] = (string)$duLieu['so_nam_kinh_nghiem'];
        }

        $bacSiMoi = $this->bacSiRepo->capNhat($id, $duLieuCapNhat);

        // Cap nhat dong bo sang tai khoan neu co thay doi ho ten, sdt, email
        if ($bacSi->taiKhoan) {
            $duLieuTk = [];
            if (isset($duLieu['ho_ten'])) $duLieuTk['ho_ten'] = $duLieu['ho_ten'];
            if (isset($duLieu['so_dien_thoai'])) $duLieuTk['so_dien_thoai'] = $duLieu['so_dien_thoai'];
            if (isset($duLieu['email'])) $duLieuTk['email'] = $duLieu['email'];
            if (!empty($duLieuTk)) {
                $this->taiKhoanRepo->capNhat($bacSi->taiKhoan->id, $duLieuTk);
            }
        }

        return [
            'thanh_cong' => true,
            'thong_diep' => 'Cập nhật thông tin bác sĩ thành công.',
            'du_lieu' => $bacSiMoi
        ];
    }

    public function xoa(int $id): array
    {
        $bacSi = $this->bacSiRepo->timTheoId($id);
        if (!$bacSi) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'BAC_SI_KHONG_TON_TAI',
                'thong_diep' => 'Không tìm thấy bác sĩ cần xóa.'
            ];
        }

        $hoTen = $bacSi->ho_ten;
        $taiKhoan = $bacSi->taiKhoan;

        // Xóa bản ghi bác sĩ
        $this->bacSiRepo->xoa($id);

        // Xóa tài khoản bác sĩ nếu có
        if ($taiKhoan) {
            $taiKhoan->tokens()->delete();
            $taiKhoan->delete();
        }

        return [
            'thanh_cong' => true,
            'thong_diep' => "Đã xóa bác sĩ [{$hoTen}] và tài khoản liên kết thành công."
        ];
    }
}
