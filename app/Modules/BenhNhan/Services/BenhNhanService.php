<?php

namespace App\Modules\BenhNhan\Services;

use App\Modules\BenhNhan\Repositories\BenhNhanRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class BenhNhanService
{
    protected BenhNhanRepositoryInterface $benhNhanRepo;

    public function __construct(BenhNhanRepositoryInterface $benhNhanRepo)
    {
        $this->benhNhanRepo = $benhNhanRepo;
    }

    public function danhSachBenhNhan(int $soMoiTrang = 15): LengthAwarePaginator
    {
        return $this->benhNhanRepo->phanTrang($soMoiTrang);
    }

    public function chiTietBenhNhan(int $id)
    {
        return $this->benhNhanRepo->timTheoId($id)?->load(['lichHen.bacSi', 'lichHen.hoaDon']);
    }

    public function capNhatHoSo(int $id, array $duLieu): bool
    {
        return $this->benhNhanRepo->capNhat($id, $duLieu);
    }
}
