<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('benh_nhan') && !Schema::hasColumn('benh_nhan', 'quan_he_chu_tai_khoan')) {
            Schema::table('benh_nhan', function (Blueprint $table) {
                $table->string('quan_he_chu_tai_khoan', 30)->default('BAN_THAN')->after('sdt_khan_cap');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('benh_nhan') && Schema::hasColumn('benh_nhan', 'quan_he_chu_tai_khoan')) {
            Schema::table('benh_nhan', function (Blueprint $table) {
                $table->dropColumn('quan_he_chu_tai_khoan');
            });
        }
    }
};
