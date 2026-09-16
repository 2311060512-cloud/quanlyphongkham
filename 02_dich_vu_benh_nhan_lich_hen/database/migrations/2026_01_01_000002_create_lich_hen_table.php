<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lich_hen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('benh_nhan_id')->constrained('benh_nhan')->onDelete('cascade');
            $table->unsignedBigInteger('bac_si_id'); // ID bac si ben Service 01
            $table->date('ngay_kham');
            $table->time('gio_bat_dau');
            $table->time('gio_ket_thuc');
            $table->string('ly_do_kham')->nullable();
            $table->string('trang_thai', 30)->default('CHO_KHAM'); // CHO_KHAM, DANG_KHAM, HOAN_THANH, DA_HUY
            $table->text('ghi_chu_bac_si')->nullable();
            $table->timestamps();

            // Index toi uu truy van kiem tra trung lich
            $table->index(['bac_si_id', 'ngay_kham', 'trang_thai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lich_hen');
    }
};
