<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Thêm trường profile & avatar vào bảng tai_khoan
        Schema::table('tai_khoan', function (Blueprint $table) {
            if (!Schema::hasColumn('tai_khoan', 'avatar')) {
                $table->longText('avatar')->nullable()->after('ho_ten');
            }
            if (!Schema::hasColumn('tai_khoan', 'ngay_sinh')) {
                $table->date('ngay_sinh')->nullable()->after('so_dien_thoai');
            }
            if (!Schema::hasColumn('tai_khoan', 'gioi_tinh')) {
                $table->string('gioi_tinh', 20)->nullable()->after('ngay_sinh');
            }
            if (!Schema::hasColumn('tai_khoan', 'dia_chi')) {
                $table->string('dia_chi', 255)->nullable()->after('gioi_tinh');
            }
        });

        // 2. Thêm trường avatar vào bảng bac_si
        Schema::table('bac_si', function (Blueprint $table) {
            if (!Schema::hasColumn('bac_si', 'avatar')) {
                $table->longText('avatar')->nullable()->after('ho_ten');
            }
        });

        // 3. Tạo bảng lich_truc_bac_si
        if (!Schema::hasTable('lich_truc_bac_si')) {
            Schema::create('lich_truc_bac_si', function (Blueprint $table) {
                $table->id();
                $table->foreignId('bac_si_id')->constrained('bac_si')->onDelete('cascade');
                $table->string('thu', 20); // THU_HAI, THU_BA, THU_TU, THU_NAM, THU_SAU, THU_BAY, CHU_NHAT
                $table->unsignedTinyInteger('ngay_trong_tuan'); // 2 = Thu 2, 3 = Thu 3, ..., 8 = Chu Nhat
                $table->string('ca_truc', 30); // CA_SANG, CA_CHIEU, CA_TOI
                $table->string('gio_bat_dau', 10)->default('07:30');
                $table->string('gio_ket_thuc', 10)->default('11:30');
                $table->unsignedInteger('so_luong_kham_toi_da')->default(20);
                $table->string('phong_kham', 100)->nullable();
                $table->string('trang_thai', 50)->default('HOAT_DONG'); // HOAT_DONG, TAM_NGHI
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('lich_truc_bac_si');

        Schema::table('bac_si', function (Blueprint $table) {
            if (Schema::hasColumn('bac_si', 'avatar')) {
                $table->dropColumn('avatar');
            }
        });

        Schema::table('tai_khoan', function (Blueprint $table) {
            $cols = [];
            foreach (['avatar', 'ngay_sinh', 'gioi_tinh', 'dia_chi'] as $col) {
                if (Schema::hasColumn('tai_khoan', $col)) {
                    $cols[] = $col;
                }
            }
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
