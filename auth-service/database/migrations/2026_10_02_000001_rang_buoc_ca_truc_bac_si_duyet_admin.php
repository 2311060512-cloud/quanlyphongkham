<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('lich_truc_bac_si')) {
            // 1. Dọn dẹp dữ liệu trùng lặp trước khi đặt ràng buộc UNIQUE: Mỗi ngày chỉ giữ 1 ca trực duy nhất
            $allShifts = DB::table('lich_truc_bac_si')
                ->orderBy('id', 'desc')
                ->get();

            $kept = [];
            $deletedIds = [];

            foreach ($allShifts as $shift) {
                $key = $shift->bac_si_id . '-' . $shift->ngay_trong_tuan;
                if (!isset($kept[$key])) {
                    $kept[$key] = $shift->id;
                } else {
                    $deletedIds[] = $shift->id;
                }
            }

            if (!empty($deletedIds)) {
                DB::table('lich_truc_bac_si')->whereIn('id', $deletedIds)->delete();
            }

            // 2. Thêm chỉ mục UNIQUE đảm bảo mỗi bác sĩ chỉ có tối đa 1 ca trực trong 1 ngày
            try {
                Schema::table('lich_truc_bac_si', function (Blueprint $table) {
                    $table->unique(['bac_si_id', 'ngay_trong_tuan'], 'lich_truc_bac_si_unique_ngay');
                });
            } catch (\Throwable $e) {
                // Index đã tồn tại
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('lich_truc_bac_si')) {
            try {
                Schema::table('lich_truc_bac_si', function (Blueprint $table) {
                    $table->dropUnique('lich_truc_bac_si_unique_ngay');
                });
            } catch (\Throwable $e) {
            }
        }
    }
};
