<?php

namespace App\Modules\DichVu\Repositories;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface DichVuRepositoryInterface extends BaseRepositoryInterface
{
    public function layDichVuHoatDong(): Collection;
}
