<?php

namespace App\Modules\LichHen\Repositories;

use App\Repositories\BaseRepositoryInterface;
use App\Modules\LichHen\Models\LichHen;
use Illuminate\Database\Eloquent\Collection;

interface LichHenRepositoryInterface extends BaseRepositoryInterface
{
    public function kiemTraTrungLich(int $bacSiId, string $ngayKham, string $gioKham, ?int $boQuaId = null): bool;
    public function kiemTraTrungLichNangCao(int $bacSiId, string $ngayKham, string $gioKham, ?int $boQuaId = null, int $khoangCachPhut = 30): ?array;
    public function layTheoBenhNhan(int $benhNhanId, ?string $tuKhoa = null): Collection;
    public function layTheoBacSi(int $bacSiId, ?string $ngayKham = null): Collection;
    public function layDanhSachCoLoc(array $boLoc): Collection;
    public function layMaLichHenTiepTheo(): string;
}
