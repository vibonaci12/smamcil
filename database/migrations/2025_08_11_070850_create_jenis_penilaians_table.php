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
        Schema::create('jenis_penilaians', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->decimal('bobot', 5, 2); // bobot dalam persen (0.00 - 100.00)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_penilaians');
    }
};
