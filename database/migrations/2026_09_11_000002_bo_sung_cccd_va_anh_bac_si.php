<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Thêm cột số CCCD cho bảng bệnh nhân
        Schema::table('benh_nhan', function (Blueprint $table) {
            if (!Schema::hasColumn('benh_nhan', 'so_cccd')) {
                $table->string('so_cccd', 20)->nullable()->after('so_dien_thoai');
            }
        });

        // 2. Thêm cột hình ảnh chân dung cho bảng bác sĩ
        Schema::table('bac_si', function (Blueprint $table) {
            if (!Schema::hasColumn('bac_si', 'hinh_anh')) {
                $table->string('hinh_anh')->nullable()->after('ho_ten');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('benh_nhan', function (Blueprint $table) {
            if (Schema::hasColumn('benh_nhan', 'so_cccd')) {
                $table->dropColumn('so_cccd');
            }
        });

        Schema::table('bac_si', function (Blueprint $table) {
            if (Schema::hasColumn('bac_si', 'hinh_anh')) {
                $table->dropColumn('hinh_anh');
            }
        });
    }
};
