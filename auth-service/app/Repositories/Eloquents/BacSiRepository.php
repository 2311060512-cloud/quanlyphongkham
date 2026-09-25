<?php

namespace App\Repositories\Eloquents;

use App\Models\BacSi;
use App\Repositories\Contracts\BacSiRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class BacSiRepository implements BacSiRepositoryInterface
{
    public function danhSach(array $boLoc = []): Collection
    {
        $query = BacSi::with(['chuyenKhoa', 'taiKhoan']);

        if (!empty($boLoc['chuyen_khoa_id'])) {
            $query->where('chuyen_khoa_id', $boLoc['chuyen_khoa_id']);
        }

        if (!empty($boLoc['tu_khoa'])) {
            $tuKhoa = '%' . $boLoc['tu_khoa'] . '%';
            $query->where(function ($q) use ($tuKhoa) {
                $q->where('ho_ten', 'like', $tuKhoa)
                  ->orWhere('ma_bac_si', 'like', $tuKhoa);
            });
        }

        if (!empty($boLoc['trang_thai'])) {
            $query->where('trang_thai', $boLoc['trang_thai']);
        } else {
            $query->where('trang_thai', 'DANG_LAM_VIEC');
        }

        return $query->get();
    }

    public function timTheoId(int $id): ?BacSi
    {
        return BacSi::with(['chuyenKhoa', 'taiKhoan'])->find($id);
    }

    public function taoMoi(array $duLieu): BacSi
    {
        return BacSi::create($duLieu);
    }

    public function capNhat(int $id, array $duLieu): ?BacSi
    {
        $bacSi = BacSi::find($id);
        if ($bacSi) {
            $bacSi->update($duLieu);
            return $bacSi->fresh(['chuyenKhoa', 'taiKhoan']);
        }
        return null;
    }

    public function xoa(int $id): bool
    {
        $bacSi = BacSi::find($id);
        return $bacSi ? (bool)$bacSi->delete() : false;
    }
}
