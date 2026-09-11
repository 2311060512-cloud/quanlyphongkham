<?php

namespace App\Modules\HoaDon\Repositories;

use App\Repositories\BaseRepository;
use App\Modules\HoaDon\Models\HoaDon;
use Illuminate\Database\Eloquent\Collection;

class HoaDonRepository extends BaseRepository implements HoaDonRepositoryInterface
{
    public function __construct(HoaDon $model)
    {
        parent::__construct($model);
    }

    public function timTheoLichHenId(int $lichHenId): ?HoaDon
    {
        return $this->model->where('lich_hen_id', $lichHenId)->first();
    }

    public function layHoaDonChuaThanhToan(): Collection
    {
        return $this->model->with(['benhNhan', 'lichHen.bacSi'])->where('trang_thai', 'CHUA_THANH_TOAN')->get();
    }

    public function thongKeDoanhThu(): array
    {
        $tongDoanhThu = $this->model->where('trang_thai', 'DA_THANH_TOAN')->sum('tong_tien');
        $soHoaDonDaThanhToan = $this->model->where('trang_thai', 'DA_THANH_TOAN')->count();
        $soHoaDonChuaThanhToan = $this->model->where('trang_thai', 'CHUA_THANH_TOAN')->count();

        return [
            'tong_doanh_thu' => (float)$tongDoanhThu,
            'so_hoa_don_da_thanh_toan' => $soHoaDonDaThanhToan,
            'so_hoa_don_chua_thanh_toan' => $soHoaDonChuaThanhToan,
        ];
    }
}
