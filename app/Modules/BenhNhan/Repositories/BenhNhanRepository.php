<?php

namespace App\Modules\BenhNhan\Repositories;

use App\Repositories\BaseRepository;
use App\Modules\BenhNhan\Models\BenhNhan;

class BenhNhanRepository extends BaseRepository implements BenhNhanRepositoryInterface
{
    public function __construct(BenhNhan $model)
    {
        parent::__construct($model);
    }

    public function timTheoTaiKhoanId(int $taiKhoanId): ?BenhNhan
    {
        return $this->model->where('tai_khoan_id', $taiKhoanId)->first();
    }

    public function timTheoSoDienThoai(string $soDienThoai): ?BenhNhan
    {
        return $this->model->where('so_dien_thoai', $soDienThoai)->first();
    }
}
