<?php

namespace App\Services;

use App\Models\BenhNhan;

class BenhNhanService
{
    public function danhSach(?string $tuKhoa = null)
    {
        $query = BenhNhan::orderBy('id', 'desc');

        if ($tuKhoa) {
            $query->where(function ($q) use ($tuKhoa) {
                $q->where('ho_ten', 'like', "%{$tuKhoa}%")
                  ->orWhere('so_dien_thoai', 'like', "%{$tuKhoa}%")
                  ->orWhere('so_cccd', 'like', "%{$tuKhoa}%")
                  ->orWhere('ma_benh_nhan', 'like', "%{$tuKhoa}%");
            });
        }

        return $query->get();
    }

    public function chiTiet(int $id): ?BenhNhan
    {
        return BenhNhan::with('danhSachLichHen')->find($id);
    }

    public function chiTietTheoTaiKhoan(int $taiKhoanId): ?BenhNhan
    {
        return BenhNhan::with('danhSachLichHen')->where('tai_khoan_id', $taiKhoanId)->first();
    }

    public function taoMoi(array $data): BenhNhan
    {
        $count = BenhNhan::count();
        $maBn = 'BN' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
        while (BenhNhan::where('ma_benh_nhan', $maBn)->exists()) {
            $count++;
            $maBn = 'BN' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
        }

        return BenhNhan::create([
            'tai_khoan_id' => $data['tai_khoan_id'] ?? null,
            'ma_benh_nhan' => $maBn,
            'ho_ten' => $data['ho_ten'],
            'so_dien_thoai' => $data['so_dien_thoai'],
            'so_cccd' => $data['so_cccd'] ?? null,
            'ngay_sinh' => $data['ngay_sinh'] ?? null,
            'gioi_tinh' => $data['gioi_tinh'] ?? 'NAM',
            'dia_chi' => $data['dia_chi'] ?? null,
            'nhom_mau' => $data['nhom_mau'] ?? null,
            'tien_su_di_ung' => $data['tien_su_di_ung'] ?? null,
            'tien_su_benh' => $data['tien_su_benh'] ?? null,
            'nguoi_lien_he_khan_cap' => $data['nguoi_lien_he_khan_cap'] ?? null,
            'sdt_khan_cap' => $data['sdt_khan_cap'] ?? null,
            'quan_he_chu_tai_khoan' => $data['quan_he_chu_tai_khoan'] ?? 'BAN_THAN',
        ]);
    }

    public function capNhat(int $id, array $data): ?BenhNhan
    {
        $benhNhan = BenhNhan::find($id);
        if (!$benhNhan) return null;

        $allowed = [
            'ho_ten', 'so_dien_thoai', 'so_cccd', 'ngay_sinh', 'gioi_tinh',
            'dia_chi', 'nhom_mau', 'tien_su_di_ung', 'tien_su_benh',
            'nguoi_lien_he_khan_cap', 'sdt_khan_cap', 'quan_he_chu_tai_khoan'
        ];
        $updateData = array_intersect_key($data, array_flip($allowed));

        $benhNhan->update($updateData);
        return $benhNhan;
    }

    /**
     * LẤY DANH SÁCH HỒ SƠ GIA ĐÌNH (Bản thân + Người thân con cái, cha mẹ)
     */
    public function hoSoGiaDinh(int $taiKhoanId)
    {
        return BenhNhan::where('tai_khoan_id', $taiKhoanId)
            ->withCount('danhSachLichHen')
            ->orderByRaw("CASE WHEN quan_he_chu_tai_khoan = 'BAN_THAN' THEN 0 ELSE 1 END")
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * TẠO HỒ SƠ NGƯỜI THÂN LIÊN KẾT VỚI TÀI KHOẢN
     */
    public function taoHoSoNguoiThan(int $taiKhoanId, array $data): BenhNhan
    {
        $data['tai_khoan_id'] = $taiKhoanId;
        $data['quan_he_chu_tai_khoan'] = $data['quan_he_chu_tai_khoan'] ?? ($data['quan_he'] ?? 'NGUOI_THAN');
        return $this->taoMoi($data);
    }

}
