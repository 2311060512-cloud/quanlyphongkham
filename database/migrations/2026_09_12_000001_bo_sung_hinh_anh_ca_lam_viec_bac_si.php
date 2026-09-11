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
        Schema::table('bac_si', function (Blueprint $table) {
            if (!Schema::hasColumn('bac_si', 'hinh_anh')) {
                $table->string('hinh_anh', 255)->nullable()->after('ho_ten');
            }
            if (!Schema::hasColumn('bac_si', 'ca_lam_viec')) {
                $table->string('ca_lam_viec', 30)->default('CA_NGAY')->after('phong_kham'); // CA_SANG, CA_CHIEU, CA_NGAY, NGAY_NGHI
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bac_si', function (Blueprint $table) {
            $cols = [];
            if (Schema::hasColumn('bac_si', 'ca_lam_viec')) {
                $cols[] = 'ca_lam_viec';
            }
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
