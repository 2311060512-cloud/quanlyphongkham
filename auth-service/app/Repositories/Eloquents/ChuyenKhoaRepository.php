<?php

namespace App\Repositories\Eloquents;

use App\Models\ChuyenKhoa;
use App\Repositories\Contracts\ChuyenKhoaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ChuyenKhoaRepository implements ChuyenKhoaRepositoryInterface
{
    public function danhSach(): Collection
    {
        return ChuyenKhoa::where('trang_thai', 'HOAT_DONG')->get();
    }

    public function timTheoId(int $id): ?ChuyenKhoa
    {
        return ChuyenKhoa::find($id);
    }

    public function taoMoi(array $duLieu): ChuyenKhoa
    {
        return ChuyenKhoa::create($duLieu);
    }
}
