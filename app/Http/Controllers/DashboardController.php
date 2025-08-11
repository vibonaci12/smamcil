<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Jadwal;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            $jumlahSiswa = Siswa::count();
            $jumlahGuru = Guru::count();
            $jumlahKelas = Kelas::count();
            $jumlahMapel = Jadwal::distinct('mapel')->count('mapel');
            $jadwalHariIni = Jadwal::where('hari', now()->locale('id')->isoFormat('dddd'))
                ->with(['kelas', 'guru'])
                ->orderBy('jam_mulai')
                ->get();
            $pengumumanTerbaru = \App\Models\Pengumuman::latest()->take(5)->get();
            return view('dashboard.admin', compact('jumlahSiswa', 'jumlahGuru', 'jumlahKelas', 'jumlahMapel', 'jadwalHariIni', 'pengumumanTerbaru'));
        } elseif ($user->role === 'guru') {
            // Ambil data guru berdasarkan user_id
            $guru = Guru::where('user_id', $user->id)->first();
            
            if ($guru) {
                $jadwalHariIni = Jadwal::where('guru_id', $guru->id)
                    ->where('hari', now()->locale('id')->isoFormat('dddd'))
                    ->with('kelas')
                    ->get();
                $absensiTerbaru = \App\Models\Absensi::whereHas('jadwal', function($q) use ($guru) {
                    $q->where('guru_id', $guru->id);
                })->latest()->take(5)->with(['siswa', 'jadwal.kelas'])->get();
                $nilaiTerbaru = \App\Models\Nilai::whereHas('jadwal', function($q) use ($guru) {
                    $q->where('guru_id', $guru->id);
                })->latest()->take(5)->with(['siswa', 'jadwal.kelas'])->get();
            } else {
                $jadwalHariIni = collect();
                $absensiTerbaru = collect();
                $nilaiTerbaru = collect();
            }
            
            return view('dashboard.guru', compact('jadwalHariIni', 'absensiTerbaru', 'nilaiTerbaru'));
        } else {
            // Siswa
            $siswa = Siswa::where('user_id', $user->id)->first();
            $jadwalMingguan = collect();
            $pengumumanTerbaru = [];
            if ($siswa) {
                $jadwalMingguan = Jadwal::where('kelas_id', $siswa->kelas_id)
                    ->with(['guru', 'kelas', 'materis'])
                    ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
                    ->orderBy('jam_mulai')
                    ->get();
                $pengumumanTerbaru = \App\Models\PengumumanKelas::where('kelas_id', $siswa->kelas_id)
                    ->latest()->take(3)->get();
            }
            return view('dashboard.siswa', compact('jadwalMingguan', 'pengumumanTerbaru'));
        }
    }
} 