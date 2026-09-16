<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dich_vu', function (Blueprint $table) {
            $table->id();
            $table->string('ma_dich_vu', 50)->unique();
            $table->string('ten_dich_vu', 200);
            $table->string('loai_dich_vu', 50); // XET_NGHIEM, CHUP_XQUANG, SIEU_AM, NOI_SOI, KHAC
            $table->decimal('don_gia', 15, 2)->default(0.00);
            $table->text('mo_ta')->nullable();
            $table->tinyInteger('trang_thai')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dich_vu');
    }
};
