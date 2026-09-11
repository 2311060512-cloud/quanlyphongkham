<?php

namespace App\Modules\DichVu\Repositories;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface SuDungDichVuRepositoryInterface extends BaseRepositoryInterface
{
    public function layTheoLichHen(int $lichHenId): Collection;
}
