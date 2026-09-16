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
            $table->unsignedBigInteger('tai_khoan_id')->nullable(); // Lien ket tai khoan tu Service 01
            $table->string('ma_benh_nhan', 50)->unique();
            $table->string('ho_ten', 150);
            $table->date('ngay_sinh')->nullable();
            $table->string('gioi_tinh', 10)->default('NAM'); // NAM, NU, KHAC
            $table->string('so_dien_thoai', 20);
            $table->string('dia_chi')->nullable();
            $table->text('tien_su_benh')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('benh_nhan');
    }
};
