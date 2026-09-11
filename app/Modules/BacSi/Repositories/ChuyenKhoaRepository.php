<?php

namespace App\Modules\BacSi\Repositories;

use App\Repositories\BaseRepository;
use App\Modules\BacSi\Models\ChuyenKhoa;
use Illuminate\Database\Eloquent\Collection;

class ChuyenKhoaRepository extends BaseRepository implements ChuyenKhoaRepositoryInterface
{
    public function __construct(ChuyenKhoa $model)
    {
        parent::__construct($model);
    }

    public function timTheoMaKhoa(string $maKhoa): ?ChuyenKhoa
    {
        return $this->model->where('ma_khoa', $maKhoa)->first();
    }

    public function layDanhSachHoatDong(): Collection
    {
        return $this->model->where('trang_thai', 'HOAT_DONG')->get();
    }
}
