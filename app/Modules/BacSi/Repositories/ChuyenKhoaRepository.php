<?php

namespace App\Modules\BacSi\Repositories;

use App\Repositories\BaseRepository;
use App\Modules\BacSi\Models\ChuyenKhoa;

class ChuyenKhoaRepository extends BaseRepository implements ChuyenKhoaRepositoryInterface
{
    public function __construct(ChuyenKhoa $model)
    {
        parent::__construct($model);
    }
}
