<?php

namespace App\Modules\LichHen\Repositories;

use App\Repositories\BaseRepository;
use App\Modules\LichHen\Models\LichHen;
use Illuminate\Database\Eloquent\Collection;

class LichHenRepository extends BaseRepository implements LichHenRepositoryInterface
{
    public function __construct(LichHen $model)
    {
        parent::__construct($model);
    }

    public function kiemTraTrungLich(int $bacSiId, string $ngayKham, string $gioKham, ?int $boQuaId = null): bool
    {
        $query = $this->model
            ->where('bac_si_id', $bacSiId)
            ->where('ngay_kham', $ngayKham)
            ->where('gio_kham', $gioKham)
            ->whereNotIn('trang_thai', ['DA_HUY']);

        if ($boQuaId) {
            $query->where('id', '!=', $boQuaId);
        }

        return $query->exists();
    }

    public function layTheoBenhNhan(int $benhNhanId): Collection
    {
        return $this->model->with(['bacSi.chuyenKhoa', 'hoaDon'])
            ->where('benh_nhan_id', $benhNhanId)
            ->orderBy('ngay_kham', 'desc')
            ->orderBy('gio_kham', 'desc')
            ->get();
    }

    public function layTheoBacSi(int $bacSiId, ?string $ngayKham = null): Collection
    {
        $query = $this->model->with(['benhNhan', 'suDungDichVu.dichVu', 'hoaDon'])
            ->where('bac_si_id', $bacSiId);

        if ($ngayKham) {
            $query->where('ngay_kham', $ngayKham);
        }

        return $query->orderBy('gio_kham', 'asc')->get();
    }
}
