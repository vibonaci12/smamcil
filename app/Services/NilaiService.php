<?php

namespace App\Services;

use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\Jadwal;
use Illuminate\Support\Facades\DB;

class NilaiService
{
    /**
     * Calculate final grade for a student in a specific subject
     *
     * @param int $siswaId
     * @param int $jadwalId
     * @return array
     */
    public static function hitungNilaiAkhir($siswaId, $jadwalId)
    {
        $nilais = Nilai::where('siswa_id', $siswaId)
            ->where('jadwal_id', $jadwalId)
            ->with(['jenisPenilaian'])
            ->get();

        if ($nilais->isEmpty()) {
            return [
                'nilai_akhir' => null,
                'nilai_detail' => [],
                'total_bobot' => 0,
                'status' => 'Belum ada nilai'
            ];
        }

        $nilaiDetail = [];
        $totalNilai = 0;
        $totalBobot = 0;

        foreach ($nilais as $nilai) {
            $bobot = $nilai->jenisPenilaian->bobot ?? 0;
            $nilaiDetail[] = [
                'jenis' => $nilai->jenisPenilaian->nama,
                'nilai' => $nilai->nilai,
                'bobot' => $bobot,
                'nilai_terbobot' => ($nilai->nilai * $bobot) / 100
            ];

            $totalNilai += ($nilai->nilai * $bobot) / 100;
            $totalBobot += $bobot;
        }

        $nilaiAkhir = $totalBobot > 0 ? round($totalNilai / $totalBobot * 100, 2) : null;

        return [
            'nilai_akhir' => $nilaiAkhir,
            'nilai_detail' => $nilaiDetail,
            'total_bobot' => $totalBobot,
            'status' => self::getStatusNilai($nilaiAkhir)
        ];
    }

    /**
     * Get transcript for a student
     *
     * @param int $siswaId
     * @return array
     */
    public static function getTranskripSiswa($siswaId)
    {
        $siswa = Siswa::with(['kelas'])->findOrFail($siswaId);
        
        // Get all subjects with grades for this student
        $jadwals = Jadwal::whereHas('nilais', function($query) use ($siswaId) {
            $query->where('siswa_id', $siswaId);
        })
        ->with(['guru'])
        ->get();

        $transkrip = [];
        $totalNilaiAkhir = 0;
        $jumlahMataPelajaran = 0;

        foreach ($jadwals as $jadwal) {
            $nilaiAkhir = self::hitungNilaiAkhir($siswaId, $jadwal->id);
            
            $transkrip[] = [
                'mata_pelajaran' => $jadwal->mapel ?? 'Mata Pelajaran',
                'guru' => $jadwal->guru->nama ?? 'Guru',
                'nilai_akhir' => $nilaiAkhir['nilai_akhir'],
                'status' => $nilaiAkhir['status'],
                'nilai_detail' => $nilaiAkhir['nilai_detail']
            ];

            if ($nilaiAkhir['nilai_akhir'] !== null) {
                $totalNilaiAkhir += $nilaiAkhir['nilai_akhir'];
                $jumlahMataPelajaran++;
            }
        }

        $rataRata = $jumlahMataPelajaran > 0 ? round($totalNilaiAkhir / $jumlahMataPelajaran, 2) : null;

        return [
            'siswa' => $siswa,
            'transkrip' => $transkrip,
            'rata_rata' => $rataRata,
            'status_rata_rata' => self::getStatusNilai($rataRata),
            'jumlah_mata_pelajaran' => $jumlahMataPelajaran
        ];
    }

    /**
     * Get status based on grade
     *
     * @param float|null $nilai
     * @return string
     */
    public static function getStatusNilai($nilai)
    {
        if ($nilai === null) {
            return 'Belum ada nilai';
        }

        $kriteria = config('nilai.kriteria');
        
        foreach ($kriteria as $status => $config) {
            if ($nilai >= $config['min']) {
                return $config['label'];
            }
        }
        
        return 'Kurang (D)';
    }

    /**
     * Get grade statistics for a class
     *
     * @param int $kelasId
     * @return array
     */
    public static function getStatistikKelas($kelasId)
    {
        $siswas = Siswa::where('kelas_id', $kelasId)->pluck('id');
        
        $statistik = [];
        
        foreach ($siswas as $siswaId) {
            $transkrip = self::getTranskripSiswa($siswaId);
            $statistik[] = [
                'siswa_id' => $siswaId,
                'rata_rata' => $transkrip['rata_rata'],
                'status' => $transkrip['status_rata_rata']
            ];
        }

        return $statistik;
    }

    /**
     * Get default bobot configuration
     *
     * @return array
     */
    public static function getDefaultBobot()
    {
        return config('nilai.default_bobot');
    }
}
