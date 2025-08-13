<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Siswa;
use App\Services\NilaiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaNilaiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        
        if (!$siswa) {
            return redirect()->route('dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }

        // Ambil nilai untuk siswa dengan eager loading
        $nilais = Nilai::where('siswa_id', $siswa->id)
            ->with(['jadwal.guru', 'jadwal.kelas', 'jenisPenilaian'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('siswa.nilai.index', compact('nilais', 'siswa'));
    }

    /**
     * Get transcript for the authenticated student
     *
     * @return \Illuminate\Http\Response
     */
    public function transkrip()
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        
        if (!$siswa) {
            return redirect()->route('dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }

        try {
            $transkrip = NilaiService::getTranskripSiswa($siswa->id);
            
            return view('siswa.nilai.transkrip', compact('transkrip'));
        } catch (\Exception $e) {
            return redirect()->route('siswa.nilai')->with('error', $e->getMessage());
        }
    }

    /**
     * Get nilai akhir per mata pelajaran
     *
     * @return \Illuminate\Http\Response
     */
    public function nilaiAkhir()
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        
        if (!$siswa) {
            return redirect()->route('dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }

        try {
            // Get all subjects with grades for this student
            $jadwals = \App\Models\Jadwal::whereHas('nilais', function($query) use ($siswa) {
                $query->where('siswa_id', $siswa->id);
            })
            ->with(['guru'])
            ->get();

            $nilaiAkhir = [];
            foreach ($jadwals as $jadwal) {
                $nilaiAkhir[$jadwal->id] = NilaiService::hitungNilaiAkhir($siswa->id, $jadwal->id);
            }

            return view('siswa.nilai.nilai-akhir', compact('siswa', 'jadwals', 'nilaiAkhir'));
        } catch (\Exception $e) {
            return redirect()->route('siswa.nilai')->with('error', $e->getMessage());
        }
    }
}
