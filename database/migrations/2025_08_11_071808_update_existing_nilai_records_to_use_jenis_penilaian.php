<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing records to map old enum values to new foreign keys
        DB::statement("
            UPDATE nilais 
            SET jenis_penilaian_id = (
                SELECT id FROM jenis_penilaians 
                WHERE nama = LOWER(nilais.jenis)
            )
        ");

        // Now drop the old enum column
        Schema::table('nilais', function (Blueprint $table) {
            $table->dropColumn('jenis');
        });

        // Make the new column not nullable and add foreign key constraint
        Schema::table('nilais', function (Blueprint $table) {
            $table->foreignId('jenis_penilaian_id')->nullable(false)->change();
            $table->foreign('jenis_penilaian_id')->references('id')->on('jenis_penilaians')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the enum column
        Schema::table('nilais', function (Blueprint $table) {
            $table->enum('jenis', ['tugas', 'uts', 'uas'])->after('jadwal_id');
        });

        // Update records back to enum values
        DB::statement("
            UPDATE nilais 
            SET jenis = (
                SELECT nama FROM jenis_penilaians 
                WHERE id = nilais.jenis_penilaian_id
            )
        ");

        // Drop the foreign key column
        Schema::table('nilais', function (Blueprint $table) {
            $table->dropForeign(['jenis_penilaian_id']);
            $table->dropColumn('jenis_penilaian_id');
        });
    }
};
