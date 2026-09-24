<?php

namespace Database\Seeders;

use App\Models\DichVu;
use App\Models\SuDungDichVu;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Danh muc Dich vu Y te Can lam sang
        $dv1 = DichVu::updateOrCreate(['ma_dich_vu' => 'XN_CONG_THUC_MAU'], [
            'ten_dich_vu' => 'Tong phan tich te bao mau ngoai vi (24 thong so)',
            'loai_dich_vu' => 'XET_NGHIEM',
            'don_gia' => 120000.00,
            'mo_ta' => 'Xet nghiem danh gia tinh trang thieu mau, nhiem trung, tieu cau',
            'trang_thai' => 1,
        ]);

        $dv2 = DichVu::updateOrCreate(['ma_dich_vu' => 'XQ_NGUC_THANG'], [
            'ten_dich_vu' => 'Chup X-Quang nguc thang (KTS)',
            'loai_dich_vu' => 'CHUP_XQUANG',
            'don_gia' => 150000.00,
            'mo_ta' => 'Chup X-Quang tim phoi phat hien ton thuong phoi, bong tim',
            'trang_thai' => 1,
        ]);

        $dv3 = DichVu::updateOrCreate(['ma_dich_vu' => 'SA_BUNG_TQ'], [
            'ten_dich_vu' => 'Sieu am o bung tong quat (Mau 4D)',
            'loai_dich_vu' => 'SIEU_AM',
            'don_gia' => 200000.00,
            'mo_ta' => 'Khao sat gan, mat, tuy, lach, than, bang quang, tuyen tien liet',
            'trang_thai' => 1,
        ]);

        $dv4 = DichVu::updateOrCreate(['ma_dich_vu' => 'NS_TAI_MUI_HONG'], [
            'ten_dich_vu' => 'Noi soi Tai - Mui - Hong ong mem',
            'loai_dich_vu' => 'NOI_SOI',
            'don_gia' => 250000.00,
            'mo_ta' => 'Noi soi khao sat niem mac tai, mui xoang, vom hong, thanh quan',
            'trang_thai' => 1,
        ]);

        $dv5 = DichVu::updateOrCreate(['ma_dich_vu' => 'DO_ECG'], [
            'ten_dich_vu' => 'Dien tam do (ECG 12 chuyen dao)',
            'loai_dich_vu' => 'KHAC',
            'don_gia' => 80000.00,
            'mo_ta' => 'Ghi lai hoat dong dien hoc cua tim, phat hien roi loan nhip tim',
            'trang_thai' => 1,
        ]);

        // 2. Chi dinh mau cho lich hen so 2 (Lich hen da kham xong)
        SuDungDichVu::updateOrCreate([
            'lich_hen_id' => 2,
            'dich_vu_id' => $dv5->id,
        ], [
            'benh_nhan_id' => 2,
            'bac_si_id' => 2,
            'so_luong' => 1,
            'don_gia' => $dv5->don_gia,
            'ket_qua' => 'Nhip xoang deu, tan so 82 chu ky/phut, khong co dau hieu thieu mau co tim cap.',
            'ghi_chu' => 'Dien tam do trong gioi han binh thuong',
            'trang_thai' => 'DA_CO_KET_QUA',
        ]);
    }
}
