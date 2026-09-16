<?php

namespace App\Repositories\Contracts;

use App\Models\TaiKhoan;

interface TaiKhoanRepositoryInterface
{
    public function danhSach(): \Illuminate\Database\Eloquent\Collection;
    public function timTheoTenDangNhapHoacEmail(string $giaTri): ?TaiKhoan;
    public function timTheoId(int $id): ?TaiKhoan;
    public function taoMoi(array $duLieu): TaiKhoan;
    public function capNhat(int $id, array $duLieu): ?TaiKhoan;
    public function doiTrangThai(int $id, string $trangThai): bool;
    public function doiMatKhau(int $id, string $matKhauMoi): bool;
}
