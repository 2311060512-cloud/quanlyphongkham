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
            $table->string('ma_lich_hen', 30)->unique();
            $table->foreignId('benh_nhan_id')->constrained('benh_nhan')->onDelete('cascade');
            $table->unsignedBigInteger('bac_si_id')->index(); // ID bac si ben Service 01
            $table->date('ngay_kham')->index();
            $table->time('gio_bat_dau');
            $table->time('gio_ket_thuc');
            $table->text('ly_do_kham')->nullable(); // Trieu chung ban dau
            $table->string('trang_thai', 30)->default('CHO_XAC_NHAN')->index(); // CHO_XAC_NHAN, DA_XAC_NHAN, DANG_KHAM, HOAN_THANH, DA_HUY
            $table->text('chuan_doan')->nullable(); // Ket luan kham
            $table->text('loi_khuyen')->nullable(); // Don thuoc, can dan
            $table->text('ghi_chu_bac_si')->nullable();
            $table->text('ly_do_huy')->nullable();
            $table->integer('so_lan_doi_lich')->default(0);
            $table->text('ly_do_doi_lich')->nullable();
            $table->longText('tep_dinh_kem')->nullable();
            $table->json('toa_thuoc')->nullable(); // Danh sach thuoc ke don
            $table->date('ngay_tai_kham')->nullable(); // Ngay hen tai kham // JSON chua danh sach tep / anh don thuoc, ket qua xet nghiem
            $table->timestamps();

            // Index toi uu truy van kiem tra trung lich va bo loc
            $table->index(['bac_si_id', 'ngay_kham', 'trang_thai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lich_hen');
    }
};
