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
            $table->string('hinh_anh', 255)->nullable()->after('ho_ten');
            $table->string('ca_lam_viec', 30)->default('CA_NGAY')->after('phong_kham'); // CA_SANG, CA_CHIEU, CA_NGAY, NGAY_NGHI
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bac_si', function (Blueprint $table) {
            $table->dropColumn(['hinh_anh', 'ca_lam_viec']);
        });
    }
};
