<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JenisPenilaian;

class JenisPenilaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisPenilaians = [
            [
                'nama' => 'tugas',
                'bobot' => 30.00
            ],
            [
                'nama' => 'uts',
                'bobot' => 30.00
            ],
            [
                'nama' => 'uas',
                'bobot' => 40.00
            ]
        ];

        foreach ($jenisPenilaians as $jenis) {
            JenisPenilaian::updateOrCreate(
                ['nama' => $jenis['nama']],
                $jenis
            );
        }
    }
}
