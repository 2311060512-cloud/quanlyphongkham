<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('benh_nhan', function (Blueprint $table) {
            if (!Schema::hasColumn('benh_nhan', 'tien_su_di_ung')) {
                $table->text('tien_su_di_ung')->nullable()->after('tien_su_benh');
            }
            if (!Schema::hasColumn('benh_nhan', 'nguoi_lien_he_khan_cap')) {
                $table->string('nguoi_lien_he_khan_cap', 150)->nullable()->after('tien_su_di_ung');
            }
            if (!Schema::hasColumn('benh_nhan', 'sdt_khan_cap')) {
                $table->string('sdt_khan_cap', 20)->nullable()->after('nguoi_lien_he_khan_cap');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('benh_nhan', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('benh_nhan', 'sdt_khan_cap')) {
                $columns[] = 'sdt_khan_cap';
            }
            if (Schema::hasColumn('benh_nhan', 'nguoi_lien_he_khan_cap')) {
                $columns[] = 'nguoi_lien_he_khan_cap';
            }
            if (Schema::hasColumn('benh_nhan', 'tien_su_di_ung')) {
                $columns[] = 'tien_su_di_ung';
            }
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
