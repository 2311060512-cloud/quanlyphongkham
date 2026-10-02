<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lich_hen', function (Blueprint $table) {
            if (!Schema::hasColumn('lich_hen', 'chuan_doan')) {
                $table->text('chuan_doan')->nullable()->after('trang_thai');
            }
            if (!Schema::hasColumn('lich_hen', 'loi_khuyen')) {
                $table->text('loi_khuyen')->nullable()->after('chuan_doan');
            }
            if (!Schema::hasColumn('lich_hen', 'ghi_chu_bac_si')) {
                $table->text('ghi_chu_bac_si')->nullable()->after('loi_khuyen');
            }
            if (!Schema::hasColumn('lich_hen', 'toa_thuoc')) {
                $table->json('toa_thuoc')->nullable()->after('tep_dinh_kem');
            }
            if (!Schema::hasColumn('lich_hen', 'ngay_tai_kham')) {
                $table->date('ngay_tai_kham')->nullable()->after('toa_thuoc');
            }
        });
    }

    public function down(): void
    {
        Schema::table('lich_hen', function (Blueprint $table) {
            $cols = ['chuan_doan', 'loi_khuyen', 'ghi_chu_bac_si', 'toa_thuoc', 'ngay_tai_kham'];
            foreach ($cols as $c) {
                if (Schema::hasColumn('lich_hen', $c)) {
                    $table->dropColumn($c);
                }
            }
        });
    }
};
