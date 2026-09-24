<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hoa_don', function (Blueprint $table) {
            $table->id();
            $table->string('ma_hoa_don', 50)->unique();
            $table->unsignedBigInteger('lich_hen_id')->unique(); // 1 lich hen co 1 hoa don
            $table->unsignedBigInteger('benh_nhan_id');
            $table->decimal('tien_kham', 15, 2)->default(0.00);
            $table->decimal('tien_dich_vu', 15, 2)->default(0.00);
            $table->decimal('tong_tien', 15, 2)->default(0.00);
            $table->decimal('giam_gia', 15, 2)->default(0.00);
            $table->decimal('thuc_thu', 15, 2)->default(0.00);
            $table->string('phuong_thuc_thanh_toan', 30)->default('TIEN_MAT'); // TIEN_MAT, CHUYEN_KHOAN, VNPAY, MOMO
            $table->string('trang_thai', 30)->default('CHUA_THANH_TOAN'); // CHUA_THANH_TOAN, DA_THANH_TOAN, HUY
            $table->dateTime('ngay_thanh_toan')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            $table->index(['benh_nhan_id', 'trang_thai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hoa_don');
    }
};
