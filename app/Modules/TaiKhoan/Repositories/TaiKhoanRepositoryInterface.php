<?php

namespace App\Modules\TaiKhoan\Repositories;

use App\Repositories\BaseRepositoryInterface;
use App\Modules\TaiKhoan\Models\TaiKhoan;
use Illuminate\Pagination\LengthAwarePaginator;

interface TaiKhoanRepositoryInterface extends BaseRepositoryInterface
{
    public function timTheoTenDangNhap(string $tenDangNhap): ?TaiKhoan;
    public function timTheoEmail(string $email): ?TaiKhoan;
    public function layDanhSachCoPhanTrang(int $soMoiTrang = 10, ?int $vaiTroId = null, ?string $tuKhoa = null): LengthAwarePaginator;
    public function chuyenTrangThai(int $id): ?TaiKhoan;
    public function doiMatKhau(int $id, string $matKhauMoiHash): bool;
}
