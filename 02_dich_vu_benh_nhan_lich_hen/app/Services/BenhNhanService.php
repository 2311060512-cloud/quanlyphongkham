<?php

namespace App\Services;

use App\Models\BenhNhan;

class BenhNhanService
{
    public function danhSach(?string $tuKhoa = null)
    {
        $query = BenhNhan::query();
        if ($tuKhoa) {
            $query->where(function ($q) use ($tuKhoa) {
                $q->where('ho_ten', 'like', "%{$tuKhoa}%")
                  ->orWhere('so_dien_thoai', 'like', "%{$tuKhoa}%")
                  ->orWhere('ma_benh_nhan', 'like', "%{$tuKhoa}%");
            });
        }
        return $query->orderBy('id', 'desc')->get();
    }

    public function chiTiet(int $id)
    {
        return BenhNhan::with('danhSachLichHen')->find($id);
    }

    public function timTheoTaiKhoan(int $taiKhoanId)
    {
        return BenhNhan::where('tai_khoan_id', $taiKhoanId)->first();
    }

    public function taoMoi(array $data): BenhNhan
    {
        if (empty($data['ma_benh_nhan'])) {
            $data['ma_benh_nhan'] = 'BN-' . date('Ymd') . '-' . str_pad((string)rand(1, 9999), 4, '0', STR_PAD_LEFT);
        }

        return BenhNhan::create($data);
    }
}
