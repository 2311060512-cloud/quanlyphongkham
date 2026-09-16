<?php

namespace App\Repositories\Contracts;

use App\Models\ChuyenKhoa;
use Illuminate\Database\Eloquent\Collection;

interface ChuyenKhoaRepositoryInterface
{
    public function danhSach(): Collection;
    public function timTheoId(int $id): ?ChuyenKhoa;
    public function taoMoi(array $duLieu): ChuyenKhoa;
}
