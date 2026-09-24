<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('su_dung_dich_vu', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lich_hen_id'); // ID lich hen ben Service 02
            $table->unsignedBigInteger('benh_nhan_id'); // ID benh nhan ben Service 02
            $table->unsignedBigInteger('bac_si_id'); // ID bac si ben Service 01
            $table->foreignId('dich_vu_id')->constrained('dich_vu')->onDelete('cascade');
            $table->integer('so_luong')->default(1);
            $table->decimal('don_gia', 15, 2)->default(0.00);
            $table->text('ket_qua')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->string('file_ket_qua')->nullable();
            $table->string('trang_thai', 30)->default('CHO_THUC_HIEN'); // CHO_THUC_HIEN, DA_CO_KET_QUA, DA_HUY
            $table->timestamps();

            $table->index(['lich_hen_id', 'trang_thai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('su_dung_dich_vu');
    }
};
