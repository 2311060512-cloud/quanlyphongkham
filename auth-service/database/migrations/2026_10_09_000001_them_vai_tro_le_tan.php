<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Kiem tra va them vai tro LE_TAN neu chua co
        $vaiTro = DB::table('vai_tro')->where('ma_vai_tro', 'LE_TAN')->first();
        if (!$vaiTro) {
            $vaiTroId = DB::table('vai_tro')->insertGetId([
                'ma_vai_tro' => 'LE_TAN',
                'ten_vai_tro' => 'Nhân viên lễ tân',
                'mo_ta' => 'Tiếp đón bệnh nhân, quản lý thông tin bệnh nhân, lịch hẹn và thu ngân viện phí',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $vaiTroId = $vaiTro->id;
        }

        // 2. Kiem tra va tao tai khoan mau letan / 123456 neu chua co
        $taiKhoan = DB::table('tai_khoan')->where('ten_dang_nhap', 'letan')->first();
        if (!$taiKhoan) {
            DB::table('tai_khoan')->insert([
                'ten_dang_nhap' => 'letan',
                'email' => 'letan@phongkham.vn',
                'mat_khau' => Hash::make('123456'),
                'ho_ten' => 'Lễ Tân Nguyễn Thị Mai',
                'so_dien_thoai' => '0945678901',
                'vai_tro_id' => $vaiTroId,
                'trang_thai' => 'HOAT_DONG',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('tai_khoan')->where('ten_dang_nhap', 'letan')->delete();
        DB::table('vai_tro')->where('ma_vai_tro', 'LE_TAN')->delete();
    }
};
