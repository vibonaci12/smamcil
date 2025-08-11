<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $admin = User::create([
            'name' => 'Admin',
            'username' => '00000001',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $guruUser = User::create([
            'name' => 'Guru',
            'username' => '12345678',
            'role' => 'guru',
            'password' => bcrypt('password'),
        ]);

        $siswaUser = User::create([
            'name' => 'Siswa',
            'username' => '20230001',
            'role' => 'siswa',
            'password' => bcrypt('password'),
        ]);

        $guru = \App\Models\Guru::create([
            'nama' => 'Guru',
            'nip' => '12345678',
            'mapel' => 'Matematika',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1990-01-01',
            'user_id' => $guruUser->id,
        ]);

        $kelas = \App\Models\Kelas::create([
            'nama' => 'XII IPA 1',
        ]);

        \App\Models\Siswa::create([
            'nama' => 'Siswa',
            'nis' => '20230001',
            'kelas_id' => $kelas->id,
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2005-01-01',
            'user_id' => $siswaUser->id,
        ]);

        // Sample Jadwal data
        \App\Models\Jadwal::create([
            'kelas_id' => $kelas->id,
            'guru_id' => $guru->id,
            'mapel' => 'Matematika',
            'hari' => 'Senin',
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:30',
        ]);

        \App\Models\Jadwal::create([
            'kelas_id' => $kelas->id,
            'guru_id' => $guru->id,
            'mapel' => 'Fisika',
            'hari' => 'Selasa',
            'jam_mulai' => '08:30',
            'jam_selesai' => '10:00',
        ]);

        \App\Models\Jadwal::create([
            'kelas_id' => $kelas->id,
            'guru_id' => $guru->id,
            'mapel' => 'Kimia',
            'hari' => 'Rabu',
            'jam_mulai' => '10:00',
            'jam_selesai' => '11:30',
        ]);

        // Seed JenisPenilaian
        $this->call([
            JenisPenilaianSeeder::class
        ]);
    }
}
