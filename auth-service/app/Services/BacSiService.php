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

    /**
     * Lấy danh sách bảng giá khám bệnh chi tiết theo bác sĩ & chuyên khoa
     */
    public function bangGiaKham(array $boLoc = []): array
    {
        $danhSach = $this->bacSiRepo->danhSach($boLoc);

        $bangGia = $danhSach->map(function ($bs) {
            $giaKham = (float)($bs->gia_kham ?? 200000);

            // Xác định phân khúc giá / hạng khám theo học vị & giá khám
            $phanKhuc = 'Khám Tiêu Chuẩn';
            $hocVi = $bs->hoc_vi ?? '';
            if (stripos($hocVi, 'Giáo sư') !== false || stripos($hocVi, 'GS') !== false) {
                $phanKhuc = 'Khám Giáo Sư / Chuyên Gia Đầu Ngành';
            } elseif (stripos($hocVi, 'Phó Giáo sư') !== false || stripos($hocVi, 'PGS') !== false) {
                $phanKhuc = 'Khám Phó Giáo Sư';
            } elseif (stripos($hocVi, 'Tiến sĩ') !== false || stripos($hocVi, 'TS') !== false || stripos($hocVi, 'CKII') !== false || stripos($hocVi, 'Chuyên khoa 2') !== false || stripos($hocVi, 'Chuyên khoa II') !== false) {
                $phanKhuc = 'Khám Chuyên Gia / CKII';
            } elseif (stripos($hocVi, 'Thạc sĩ') !== false || stripos($hocVi, 'ThS') !== false || stripos($hocVi, 'CKI') !== false || stripos($hocVi, 'Chuyên khoa 1') !== false || stripos($hocVi, 'Chuyên khoa I') !== false) {
                $phanKhuc = 'Khám Thạc Sĩ / CKI';
            } elseif ($giaKham >= 400000) {
                $phanKhuc = 'Khám Dịch Vụ VIP';
            } elseif ($giaKham >= 250000) {
                $phanKhuc = 'Khám Chuyên Khoa Yêu Cầu';
            }

            return [
                'bac_si_id' => $bs->id,
                'ma_bac_si' => $bs->ma_bac_si,
                'ho_ten' => $bs->ho_ten,
                'avatar' => $bs->avatar ?? ($bs->taiKhoan ? $bs->taiKhoan->avatar : null),
                'hoc_vi' => $bs->hoc_vi ?: 'Bác sĩ Đa khoa',
                'chuyen_khoa_id' => $bs->chuyen_khoa_id,
                'ten_chuyen_khoa' => $bs->chuyenKhoa ? ($bs->chuyenKhoa->ten_khoa ?? $bs->chuyenKhoa->ten_chuyen_khoa) : 'Đa Khoa',
                'ma_chuyen_khoa' => $bs->chuyenKhoa ? ($bs->chuyenKhoa->ma_khoa ?? $bs->chuyenKhoa->ma_chuyen_khoa) : 'DA_KHOA',
                'phong_kham' => $bs->phong_kham ?: 'Phòng khám đa khoa',
                'gia_kham' => $giaKham,
                'formatted_gia_kham' => number_format($giaKham, 0, ',', '.') . ' VNĐ',
                'phan_khuc' => $phanKhuc,
                'kinh_nghiem' => $bs->kinh_nghiem,
                'trang_thai' => $bs->trang_thai,
            ];
        });

        $giaList = $bangGia->pluck('gia_kham');
        $thongKe = [
            'tong_so_bac_si' => $bangGia->count(),
            'gia_thap_nhat' => $giaList->count() > 0 ? $giaList->min() : 0,
            'gia_cao_nhat' => $giaList->count() > 0 ? $giaList->max() : 0,
            'gia_trung_binh' => $giaList->count() > 0 ? round($giaList->avg(), 0) : 0,
        ];

        return [
            'thanh_cong' => true,
            'thong_diep' => 'Lấy bảng giá khám bệnh thành công.',
            'thong_ke' => $thongKe,
            'du_lieu' => $bangGia
        ];
    }

    /**
     * Cập nhật nhanh đơn giá khám của bác sĩ
     */
    public function capNhatGiaKham(int $id, float $giaKham): array
    {
        $bacSi = $this->bacSiRepo->timTheoId($id);
        if (!$bacSi) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'BAC_SI_KHONG_TON_TAI',
                'thong_diep' => 'Không tìm thấy bác sĩ cần cập nhật giá khám.'
            ];
        }

        $bacSiMoi = $this->bacSiRepo->capNhat($id, ['gia_kham' => $giaKham]);

        return [
            'thanh_cong' => true,
            'thong_diep' => "Đã cập nhật giá khám của bác sĩ {$bacSi->ho_ten} thành " . number_format($giaKham, 0, ',', '.') . " VNĐ.",
            'du_lieu' => $bacSiMoi
        ];
    }
}
