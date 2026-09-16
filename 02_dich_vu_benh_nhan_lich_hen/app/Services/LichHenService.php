<?php

namespace App\Services;

use App\Models\BenhNhan;
use App\Models\LichHen;
use Carbon\Carbon;

class LichHenService
{
    /**
     * THUAT TOAN CHONG TRUNG LICH BAC SI
     * Kiem tra xem bac si da co lich hen nao khac trung gio hay khong.
     * Dieu kien trung gio: (gio_bat_dau < gio_ket_thuc_moi) AND (gio_ket_thuc > gio_bat_dau_moi)
     */
    public function kiemTraTrungLich(int $bacSiId, string $ngayKham, string $gioBatDau, string $gioKetThuc, ?int $loaiTruLichHenId = null): bool
    {
        $query = LichHen::where('bac_si_id', $bacSiId)
            ->where('ngay_kham', $ngayKham)
            ->where('trang_thai', '!=', 'DA_HUY')
            ->where(function ($q) use ($gioBatDau, $gioKetThuc) {
                $q->where('gio_bat_dau', '<', $gioKetThuc)
                  ->where('gio_ket_thuc', '>', $gioBatDau);
            });

        if ($loaiTruLichHenId) {
            $query->where('id', '!=', $loaiTruLichHenId);
        }

        return $query->exists();
    }

    public function datLich(array $data): array
    {
        $bacSiId = (int)$data['bac_si_id'];
        $ngayKham = $data['ngay_kham'];
        $gioBatDau = $data['gio_bat_dau'];

        // Neu khong truyen gio ket thuc, mac dinh kham 30 phut
        $gioKetThuc = $data['gio_ket_thuc'] ?? Carbon::parse($gioBatDau)->addMinutes(30)->format('H:i:s');

        // Kiem tra thuat toan chong trung lich
        if ($this->kiemTraTrungLich($bacSiId, $ngayKham, $gioBatDau, $gioKetThuc)) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'TRUNG_LICH_KHAM',
                'thong_diep' => "Bac si da co lich kham trong khung gio tu {$gioBatDau} den {$gioKetThuc} ngay {$ngayKham}. Vui long chon khung gio khac.",
            ];
        }

        // Kiem tra benh nhan ton tai
        $benhNhan = BenhNhan::find($data['benh_nhan_id']);
        if (!$benhNhan) {
            return [
                'thanh_cong' => false,
                'thong_diep' => 'Khong tim thay ho so benh nhan.'
            ];
        }

        $lichHen = LichHen::create([
            'benh_nhan_id' => $benhNhan->id,
            'bac_si_id' => $bacSiId,
            'ngay_kham' => $ngayKham,
            'gio_bat_dau' => $gioBatDau,
            'gio_ket_thuc' => $gioKetThuc,
            'ly_do_kham' => $data['ly_do_kham'] ?? 'Kham suc khoe tong quat',
            'trang_thai' => 'CHO_KHAM',
            'ghi_chu_bac_si' => null,
        ]);

        return [
            'thanh_cong' => true,
            'thong_diep' => 'Dat lich hen kham benh thanh cong.',
            'du_lieu' => $lichHen->load('benhNhan')
        ];
    }

    public function danhSach(array $boLoc = [])
    {
        $query = LichHen::with('benhNhan')->orderBy('ngay_kham', 'desc')->orderBy('gio_bat_dau', 'asc');

        if (!empty($boLoc['bac_si_id'])) {
            $query->where('bac_si_id', $boLoc['bac_si_id']);
        }
        if (!empty($boLoc['benh_nhan_id'])) {
            $query->where('benh_nhan_id', $boLoc['benh_nhan_id']);
        }
        if (!empty($boLoc['ngay_kham'])) {
            $query->where('ngay_kham', $boLoc['ngay_kham']);
        }
        if (!empty($boLoc['trang_thai'])) {
            $query->where('trang_thai', $boLoc['trang_thai']);
        }

        return $query->get();
    }

    public function chiTiet(int $id)
    {
        return LichHen::with('benhNhan')->find($id);
    }

    public function hoanThanh(int $id, ?string $ghiChuBacSi = null): array
    {
        $lichHen = LichHen::find($id);
        if (!$lichHen) {
            return [
                'thanh_cong' => false,
                'thong_diep' => 'Lich hen khong ton tai.'
            ];
        }

        $lichHen->update([
            'trang_thai' => 'HOAN_THANH',
            'ghi_chu_bac_si' => $ghiChuBacSi ?? $lichHen->ghi_chu_bac_si
        ]);

        return [
            'thanh_cong' => true,
            'thong_diep' => 'Da cap nhat hoan thanh buoi kham benh.',
            'du_lieu' => $lichHen->load('benhNhan')
        ];
    }

    public function huy(int $id, ?string $lyDo = null): array
    {
        $lichHen = LichHen::find($id);
        if (!$lichHen) {
            return [
                'thanh_cong' => false,
                'thong_diep' => 'Lich hen khong ton tai.'
            ];
        }

        $lichHen->update([
            'trang_thai' => 'DA_HUY',
            'ghi_chu_bac_si' => $lyDo ? "Ly do huy: {$lyDo}" : 'Benh nhan yeu cau huy'
        ]);

        return [
            'thanh_cong' => true,
            'thong_diep' => 'Huy lich hen thanh cong.',
            'du_lieu' => $lichHen
        ];
    }
}
