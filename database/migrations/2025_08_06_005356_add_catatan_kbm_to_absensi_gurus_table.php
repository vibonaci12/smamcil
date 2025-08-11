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
        Schema::table('absensi_gurus', function (Blueprint $table) {
            $table->text('catatan_kbm')->nullable()->after('materi_yang_diajarkan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi_gurus', function (Blueprint $table) {
            $table->dropColumn('catatan_kbm');
        });
    }
};
