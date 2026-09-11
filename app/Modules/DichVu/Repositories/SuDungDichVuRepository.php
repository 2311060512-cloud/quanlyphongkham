<?php

namespace App\Modules\DichVu\Repositories;

use App\Repositories\BaseRepository;
use App\Modules\DichVu\Models\SuDungDichVu;
use Illuminate\Database\Eloquent\Collection;

class SuDungDichVuRepository extends BaseRepository implements SuDungDichVuRepositoryInterface
{
    public function __construct(SuDungDichVu $model)
    {
        parent::__construct($model);
    }

    public function layTheoLichHen(int $lichHenId): Collection
    {
        return $this->model->with('dichVu')->where('lich_hen_id', $lichHenId)->get();
    }
}
