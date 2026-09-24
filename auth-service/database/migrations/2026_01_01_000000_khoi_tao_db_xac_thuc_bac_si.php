<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Bang vai_tro
        Schema::create('vai_tro', function (Blueprint $table) {
            $table->id();
            $table->string('ma_vai_tro', 50)->unique(); // ADMIN, BAC_SI, BENH_NHAN
            $table->string('ten_vai_tro', 100);
            $table->text('mo_ta')->nullable();
            $table->timestamps();
        });

        // 2. Bang tai_khoan
        Schema::create('tai_khoan', function (Blueprint $table) {
            $table->id();
            $table->string('ten_dang_nhap', 100)->unique();
            $table->string('email', 150)->unique();
            $table->string('mat_khau', 255);
            $table->string('ho_ten', 150);
            $table->string('so_dien_thoai', 20)->nullable();
            $table->foreignId('vai_tro_id')->constrained('vai_tro')->onDelete('restrict');
            $table->string('trang_thai', 50)->default('HOAT_DONG'); // HOAT_DONG, BI_KHOA
            $table->rememberToken();
            $table->timestamps();
        });

        // 3. Bang chuyen_khoa
        Schema::create('chuyen_khoa', function (Blueprint $table) {
            $table->id();
            $table->string('ma_khoa', 50)->unique(); // NOI, NHI, RHM, MAT
            $table->string('ten_khoa', 150);
            $table->text('mo_ta')->nullable();
            $table->string('hinh_anh', 255)->nullable();
            $table->string('trang_thai', 50)->default('HOAT_DONG'); // HOAT_DONG, TAM_DUNG
            $table->timestamps();
        });

        // 4. Bang bac_si
        Schema::create('bac_si', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tai_khoan_id')->constrained('tai_khoan')->onDelete('cascade');
            $table->foreignId('chuyen_khoa_id')->constrained('chuyen_khoa')->onDelete('restrict');
            $table->string('ma_bac_si', 50)->unique();
            $table->string('ho_ten', 150);
            $table->string('hoc_vi', 100)->nullable();
            $table->string('so_dien_thoai', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->decimal('gia_kham', 12, 2)->default(200000.00);
            $table->string('phong_kham', 100)->nullable();
            $table->string('kinh_nghiem', 255)->nullable();
            $table->string('trang_thai', 50)->default('DANG_LAM_VIEC'); // DANG_LAM_VIEC, NGHI_VIEC
            $table->timestamps();
        });

        // 5. Bang personal_access_tokens (Sanctum chuan)
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name', 255);
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('bac_si');
        Schema::dropIfExists('chuyen_khoa');
        Schema::dropIfExists('tai_khoan');
        Schema::dropIfExists('vai_tro');
    }
};
