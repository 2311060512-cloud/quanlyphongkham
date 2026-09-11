<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Bảng vai trò
        Schema::create('vai_tro', function (Blueprint $table) {
            $table->id();
            $table->string('ma_vai_tro', 50)->unique(); // ADMIN, BAC_SI, BENH_NHAN
            $table->string('ten_vai_tro', 100);
            $table->string('mo_ta')->nullable();
            $table->timestamps();
        });

        // 2. Bảng tài khoản
        Schema::create('tai_khoan', function (Blueprint $table) {
            $table->id();
            $table->string('ten_dang_nhap', 100)->unique();
            $table->string('email', 150)->unique();
            $table->string('mat_khau');
            $table->string('ho_ten', 150);
            $table->string('so_dien_thoai', 20)->nullable();
            $table->foreignId('vai_tro_id')->constrained('vai_tro')->onDelete('cascade');
            $table->string('trang_thai', 30)->default('HOAT_DONG');
            $table->rememberToken();
            $table->timestamps();
        });

        // 3. Personal Access Tokens cho Sanctum
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        // 4. Bảng chuyên khoa
        Schema::create('chuyen_khoa', function (Blueprint $table) {
            $table->id();
            $table->string('ma_khoa', 50)->unique();
            $table->string('ten_khoa', 150);
            $table->text('mo_ta')->nullable();
            $table->string('hinh_anh')->nullable();
            $table->string('trang_thai', 30)->default('HOAT_DONG');
            $table->timestamps();
        });

        // 5. Bảng bác sĩ
        Schema::create('bac_si', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tai_khoan_id')->nullable()->constrained('tai_khoan')->onDelete('set null');
            $table->foreignId('chuyen_khoa_id')->constrained('chuyen_khoa')->onDelete('cascade');
            $table->string('ma_bac_si', 50)->unique();
            $table->string('ho_ten', 150);
            $table->string('hoc_vi', 100)->default('Bác sĩ Chuyên khoa');
            $table->string('so_dien_thoai', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->decimal('gia_kham', 12, 2)->default(200000);
            $table->string('phong_kham', 100)->default('P101');
            $table->text('kinh_nghiem')->nullable();
            $table->string('trang_thai', 30)->default('DANG_LAM_VIEC');
            $table->timestamps();
        });

        // 6. Bảng bệnh nhân
        Schema::create('benh_nhan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tai_khoan_id')->nullable()->constrained('tai_khoan')->onDelete('set null');
            $table->string('ma_benh_nhan', 50)->unique();
            $table->string('ho_ten', 150);
            $table->string('so_dien_thoai', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('gioi_tinh', 10)->default('KHAC');
            $table->date('ngay_sinh')->nullable();
            $table->string('dia_chi')->nullable();
            $table->string('nhom_mau', 10)->nullable();
            $table->text('tien_su_benh')->nullable();
            $table->timestamps();
        });

        // 7. Bảng lịch hẹn khám
        Schema::create('lich_hen', function (Blueprint $table) {
            $table->id();
            $table->string('ma_lich_hen', 50)->unique();
            $table->foreignId('benh_nhan_id')->constrained('benh_nhan')->onDelete('cascade');
            $table->foreignId('bac_si_id')->constrained('bac_si')->onDelete('cascade');
            $table->date('ngay_kham');
            $table->string('gio_kham', 20); // 08:30, 09:00...
            $table->text('trieu_chung')->nullable();
            $table->text('chuan_doan')->nullable();
            $table->text('loi_khuyen')->nullable();
            $table->string('trang_thai', 30)->default('CHO_XAC_NHAN'); // CHO_XAC_NHAN, DA_XAC_NHAN, DANG_KHAM, HOAN_THANH, DA_HUY
            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            // Chặn trùng lặp lịch hẹn bác sĩ trong cùng ngày và giờ
            $table->unique(['bac_si_id', 'ngay_kham', 'gio_kham'], 'unique_bacsi_lich_gio');
        });

        // 8. Bảng dịch vụ y tế / cận lâm sàng
        Schema::create('dich_vu', function (Blueprint $table) {
            $table->id();
            $table->string('ma_dich_vu', 50)->unique();
            $table->string('ten_dich_vu', 150);
            $table->string('loai_dich_vu', 50)->default('XET_NGHIEM');
            $table->decimal('don_gia', 12, 2);
            $table->text('mo_ta')->nullable();
            $table->string('trang_thai', 30)->default('HOAT_DONG');
            $table->timestamps();
        });

        // 9. Bảng sử dụng dịch vụ
        Schema::create('su_dung_dich_vu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lich_hen_id')->constrained('lich_hen')->onDelete('cascade');
            $table->foreignId('dich_vu_id')->constrained('dich_vu')->onDelete('cascade');
            $table->integer('so_luong')->default(1);
            $table->decimal('don_gia', 12, 2);
            $table->decimal('thanh_tien', 12, 2);
            $table->text('ket_qua')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });

        // 10. Bảng hóa đơn viện phí
        Schema::create('hoa_don', function (Blueprint $table) {
            $table->id();
            $table->string('ma_hoa_don', 50)->unique();
            $table->foreignId('lich_hen_id')->constrained('lich_hen')->onDelete('cascade');
            $table->foreignId('benh_nhan_id')->constrained('benh_nhan')->onDelete('cascade');
            $table->decimal('tien_kham', 12, 2)->default(0);
            $table->decimal('tien_dich_vu', 12, 2)->default(0);
            $table->decimal('tong_tien', 12, 2)->default(0);
            $table->string('phuong_thuc_thanh_toan', 50)->default('TIEN_MAT');
            $table->string('trang_thai', 30)->default('CHUA_THANH_TOAN'); // CHUA_THANH_TOAN, DA_THANH_TOAN
            $table->timestamp('ngay_thanh_toan')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hoa_don');
        Schema::dropIfExists('su_dung_dich_vu');
        Schema::dropIfExists('dich_vu');
        Schema::dropIfExists('lich_hen');
        Schema::dropIfExists('benh_nhan');
        Schema::dropIfExists('bac_si');
        Schema::dropIfExists('chuyen_khoa');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('tai_khoan');
        Schema::dropIfExists('vai_tro');
    }
};
