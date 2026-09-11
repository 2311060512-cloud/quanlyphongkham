<?php

namespace App\Modules\BacSi\Repositories;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface BacSiRepositoryInterface extends BaseRepositoryInterface
{
    public function layDanhSachTheoKhoa(?int $chuyenKhoaId = null): Collection;
    public function timTheoTaiKhoanId(int $taiKhoanId);
}
