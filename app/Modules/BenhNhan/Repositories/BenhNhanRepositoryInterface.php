<?php

namespace App\Modules\BenhNhan\Repositories;

use App\Repositories\BaseRepositoryInterface;
use App\Modules\BenhNhan\Models\BenhNhan;

interface BenhNhanRepositoryInterface extends BaseRepositoryInterface
{
    public function timTheoTaiKhoanId(int $taiKhoanId): ?BenhNhan;
    public function timTheoSoDienThoai(string $soDienThoai): ?BenhNhan;
}
