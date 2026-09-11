<?php

namespace App\Modules\DichVu\Repositories;

use App\Repositories\BaseRepository;
use App\Modules\DichVu\Models\DichVu;
use Illuminate\Database\Eloquent\Collection;

class DichVuRepository extends BaseRepository implements DichVuRepositoryInterface
{
    public function __construct(DichVu $model)
    {
        parent::__construct($model);
    }

    public function layDichVuHoatDong(): Collection
    {
        return $this->model->where('trang_thai', 'HOAT_DONG')->get();
    }
}
