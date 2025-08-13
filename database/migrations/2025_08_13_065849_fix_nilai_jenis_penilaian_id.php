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
        // Update nilai records that have jenis_penilaian_id = 0 to use tugas (id = 1)
        DB::table('nilais')
            ->where('jenis_penilaian_id', 0)
            ->update(['jenis_penilaian_id' => 1]); // 1 = tugas
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to 0 (though this might not be desired)
        DB::table('nilais')
            ->where('jenis_penilaian_id', 1)
            ->update(['jenis_penilaian_id' => 0]);
    }
};
