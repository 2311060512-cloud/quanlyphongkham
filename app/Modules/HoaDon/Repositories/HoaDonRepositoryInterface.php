<?php

namespace App\Modules\HoaDon\Repositories;

use App\Repositories\BaseRepositoryInterface;
use App\Modules\HoaDon\Models\HoaDon;
use Illuminate\Database\Eloquent\Collection;

interface HoaDonRepositoryInterface extends BaseRepositoryInterface
{
    public function timTheoLichHenId(int $lichHenId): ?HoaDon;
    public function layHoaDonChuaThanhToan(): Collection;
    public function thongKeDoanhThu(): array;
}
