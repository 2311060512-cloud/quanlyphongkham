<?php

namespace App\Modules\BacSi\Services;

use App\Modules\BacSi\Repositories\BacSiRepositoryInterface;
use App\Modules\BacSi\Repositories\ChuyenKhoaRepositoryInterface;
use App\Modules\TaiKhoan\Repositories\TaiKhoanRepositoryInterface;
use App\Modules\TaiKhoan\Models\VaiTro;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;

class BacSiService
{
    protected BacSiRepositoryInterface $bacSiRepo;
    protected ChuyenKhoaRepositoryInterface $chuyenKhoaRepo;
    protected TaiKhoanRepositoryInterface $taiKhoanRepo;

    public function __construct(
        BacSiRepositoryInterface $bacSiRepo,
        ChuyenKhoaRepositoryInterface $chuyenKhoaRepo,
        TaiKhoanRepositoryInterface $taiKhoanRepo
    ) {
        $this->bacSiRepo = $bacSiRepo;
        $this->chuyenKhoaRepo = $chuyenKhoaRepo;
        $this->taiKhoanRepo = $taiKhoanRepo;
    }

    public function danhSachBacSi(?int $chuyenKhoaId = null, ?string $tuKhoa = null): Collection
    {
        return $this->bacSiRepo->layDanhSachTheoKhoa($chuyenKhoaId, $tuKhoa);
    }

    public function chiTietBacSi(int $id)
    {
        return $this->bacSiRepo->timTheoId($id)?->load('chuyenKhoa');
    }

    public function danhSachChuyenKhoa(): Collection
    {
        return $this->chuyenKhoaRepo->layTatCa();
    }

    public function taoBacSiMoi(array $duLieu)
    {
        $vaiTro = VaiTro::where('ma_vai_tro', 'BAC_SI')->first();
        $taiKhoanId = $duLieu['tai_khoan_id'] ?? null;

        if (!$taiKhoanId && !empty($duLieu['ten_dang_nhap'])) {
            $taiKhoan = $this->taiKhoanRepo->taoMoi([
                'ten_dang_nhap' => $duLieu['ten_dang_nhap'],
                'email' => $duLieu['email'] ?? ($duLieu['ten_dang_nhap'] . '@clinic.vn'),
                'mat_khau' => Hash::make($duLieu['mat_khau'] ?? '123456'),
                'ho_ten' => $duLieu['ho_ten'],
                'so_dien_thoai' => $duLieu['so_dien_thoai'] ?? null,
                'vai_tro_id' => $vaiTro ? $vaiTro->id : 2,
                'trang_thai' => 'HOAT_DONG',
            ]);
            $taiKhoanId = $taiKhoan->id;
        }

        $maBacSi = 'BS' . rand(1000, 9999);

        return $this->bacSiRepo->taoMoi([
            'tai_khoan_id' => $taiKhoanId,
            'chuyen_khoa_id' => $duLieu['chuyen_khoa_id'],
            'ma_bac_si' => $maBacSi,
            'ho_ten' => $duLieu['ho_ten'],
            'hoc_vi' => $duLieu['hoc_vi'] ?? 'Bác sĩ chuyên khoa',
            'so_dien_thoai' => $duLieu['so_dien_thoai'] ?? null,
            'email' => $duLieu['email'] ?? null,
            'gia_kham' => $duLieu['gia_kham'] ?? 200000,
            'phong_kham' => $duLieu['phong_kham'] ?? 'Phòng khám đa khoa',
            'kinh_nghiem' => $duLieu['kinh_nghiem'] ?? '5 năm kinh nghiệm',
            'trang_thai' => 'DANG_LAM_VIEC',
        ]);
    }
}
