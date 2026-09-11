<?php

namespace App\Modules\TaiKhoan\Repositories;

use App\Repositories\BaseRepositoryInterface;
use App\Modules\TaiKhoan\Models\TaiKhoan;

interface TaiKhoanRepositoryInterface extends BaseRepositoryInterface
{
    public function timTheoTenDangNhap(string $tenDangNhap): ?TaiKhoan;
    public function timTheoEmail(string $email): ?TaiKhoan;
}
