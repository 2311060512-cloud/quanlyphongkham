<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chi_tiet_hoa_don', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hoa_don_id')->constrained('hoa_don')->onDelete('cascade');
            $table->string('loai_khoan_thu', 50); // TIEN_KHAM, DICH_VU_CLS
            $table->string('ten_khoan_thu', 255);
            $table->integer('so_luong')->default(1);
            $table->decimal('don_gia', 15, 2)->default(0.00);
            $table->decimal('thanh_tien', 15, 2)->default(0.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_hoa_don');
    }
};
