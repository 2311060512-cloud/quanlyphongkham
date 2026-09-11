<?php

namespace App\Modules\BacSi\Repositories;

use App\Repositories\BaseRepositoryInterface;
use App\Modules\BacSi\Models\ChuyenKhoa;
use Illuminate\Database\Eloquent\Collection;

interface ChuyenKhoaRepositoryInterface extends BaseRepositoryInterface
{
    public function timTheoMaKhoa(string $maKhoa): ?ChuyenKhoa;
    public function layDanhSachHoatDong(): Collection;
}
