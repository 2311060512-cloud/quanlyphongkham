<?php

namespace App\Modules\LichHen\Repositories;

use App\Repositories\BaseRepositoryInterface;
use App\Modules\LichHen\Models\LichHen;
use Illuminate\Database\Eloquent\Collection;

interface LichHenRepositoryInterface extends BaseRepositoryInterface
{
    public function kiemTraTrungLich(int $bacSiId, string $ngayKham, string $gioKham, ?int $boQuaId = null): bool;
    public function layTheoBenhNhan(int $benhNhanId): Collection;
    public function layTheoBacSi(int $bacSiId, ?string $ngayKham = null): Collection;
}
