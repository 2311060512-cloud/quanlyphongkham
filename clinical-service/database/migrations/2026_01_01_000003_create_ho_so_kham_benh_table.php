<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ho_so_kham_benh', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lich_hen_id')->unique();
            $table->unsignedBigInteger('benh_nhan_id');
            $table->unsignedBigInteger('bac_si_id');
            $table->text('trieu_chung')->nullable();
            $table->text('chan_doan');
            $table->json('don_thuoc')->nullable();
            $table->text('loi_dan_bac_si')->nullable();
            $table->date('ngay_tai_kham')->nullable();
            $table->string('trang_thai', 30)->default('HOAN_THANH');
            $table->timestamps();

            $table->index(['lich_hen_id', 'benh_nhan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ho_so_kham_benh');
    }
};
