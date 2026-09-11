<?php

namespace App\Modules\BacSi\Repositories;

use App\Repositories\BaseRepository;
use App\Modules\BacSi\Models\BacSi;
use Illuminate\Database\Eloquent\Collection;

class BacSiRepository extends BaseRepository implements BacSiRepositoryInterface
{
    public function __construct(BacSi $model)
    {
        parent::__construct($model);
    }

    public function layDanhSachTheoKhoa(?int $chuyenKhoaId = null): Collection
    {
        $query = $this->model->with('chuyenKhoa')->where('trang_thai', 'DANG_LAM_VIEC');
        if ($chuyenKhoaId) {
            $query->where('chuyen_khoa_id', $chuyenKhoaId);
        }
        return $query->get();
    }

    public function timTheoTaiKhoanId(int $taiKhoanId)
    {
        return $this->model->with('chuyenKhoa')->where('tai_khoan_id', $taiKhoanId)->first();
    }
}
