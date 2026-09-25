<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('benh_nhan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tai_khoan_id')->nullable()->index(); // Lien ket tai khoan tu Service 01
            $table->string('ma_benh_nhan', 20)->unique();
            $table->string('ho_ten', 100);
            $table->string('so_dien_thoai', 15)->index();
            $table->string('so_cccd', 20)->nullable()->index();
            $table->date('ngay_sinh')->nullable();
            $table->enum('gioi_tinh', ['NAM', 'NU', 'KHAC'])->default('NAM');
            $table->string('dia_chi', 255)->nullable();
            
            // Ho so benh an dien tu mo rong
            $table->string('nhom_mau', 10)->nullable(); // A, B, AB, O
            $table->text('tien_su_di_ung')->nullable();
            $table->text('tien_su_benh')->nullable();
            $table->string('nguoi_lien_he_khan_cap', 100)->nullable();
            $table->string('sdt_khan_cap', 15)->nullable();
            $table->string('quan_he_chu_tai_khoan', 30)->default('BAN_THAN'); // BAN_THAN, CON, CHA_ME, VO_CHONG, NGUOI_THAN

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('benh_nhan');
    }
};
