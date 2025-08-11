<?php

namespace App\Http\Controllers;

use App\Models\AbsensiGuru;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AbsensiGuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $guru = Auth::user()->guru;
        
        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
        }

        // Ambil jadwal mengajar guru
        $jadwalMengajar = Jadwal::where('guru_id', $guru->id)
            ->with(['kelas', 'guru'])
            ->get();

        // Ambil absensi guru untuk jadwal mengajar
        $absensiGuru = AbsensiGuru::where('guru_id', $guru->id)
            ->with(['jadwal.kelas', 'jadwal.guru'])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('guru.absensi-guru.index', compact('jadwalMengajar', 'absensiGuru'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $guru = Auth::user()->guru;
        
        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
        }

        // Ambil jadwal mengajar hari ini
        $hariIni = Carbon::now()->format('l');
        $hariIndonesia = $this->getHariIndonesia($hariIni);
        
        $jadwalHariIni = Jadwal::where('guru_id', $guru->id)
            ->where('hari', $hariIndonesia)
            ->with(['kelas', 'guru'])
            ->get();

        return view('guru.absensi-guru.create', compact('jadwalHariIni'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:Hadir,Izin,Sakit,Tidak KBM,Tugas',
            'keterangan' => 'nullable|string',
            'materi_yang_diajarkan' => 'nullable|string',
            'catatan_kbm' => 'nullable|string',
        ]);

        $guru = Auth::user()->guru;
        
        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
        }

        // Cek apakah sudah ada absensi untuk jadwal dan tanggal yang sama
        $existingAbsensi = AbsensiGuru::where('guru_id', $guru->id)
            ->where('jadwal_id', $request->jadwal_id)
            ->where('tanggal', $request->tanggal)
            ->first();

        if ($existingAbsensi) {
            return redirect()->back()->with('error', 'Absensi untuk jadwal ini pada tanggal tersebut sudah ada.');
        }

        // Cek apakah jadwal sesuai dengan guru yang login
        $jadwal = Jadwal::where('id', $request->jadwal_id)
            ->where('guru_id', $guru->id)
            ->first();

        if (!$jadwal) {
            return redirect()->back()->with('error', 'Jadwal tidak ditemukan atau tidak sesuai.');
        }

        $absensiGuru = AbsensiGuru::create([
            'guru_id' => $guru->id,
            'jadwal_id' => $request->jadwal_id,
            'tanggal' => $request->tanggal,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'materi_yang_diajarkan' => $request->materi_yang_diajarkan,
        ]);

        // Jika ada data absensi siswa, simpan juga
        if ($request->has('siswa_absensi')) {
            foreach ($request->siswa_absensi as $siswaId => $data) {
                if (isset($data['status']) && $data['status'] !== '') {
                    // Cek apakah siswa berada di kelas yang sesuai
                    $siswa = Siswa::where('id', $siswaId)
                        ->where('kelas_id', $jadwal->kelas_id)
                        ->first();

                    if ($siswa) {
                        // Cek apakah sudah ada absensi siswa untuk jadwal dan tanggal yang sama
                        $existingSiswaAbsensi = Absensi::where('jadwal_id', $request->jadwal_id)
                            ->where('siswa_id', $siswaId)
                            ->where('tanggal', $request->tanggal)
                            ->first();

                        if (!$existingSiswaAbsensi) {
                            Absensi::create([
                                'jadwal_id' => $request->jadwal_id,
                                'siswa_id' => $siswaId,
                                'tanggal' => $request->tanggal,
                                'status' => $data['status'],
                                'keterangan' => $data['keterangan'] ?? null,
                            ]);
                        }
                    }
                }
            }
        }

        return redirect()->route('absensi-guru.index')->with('success', 'Absensi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AbsensiGuru $absensiGuru)
    {
        $guru = Auth::user()->guru;
        
        if (!$guru || $absensiGuru->guru_id !== $guru->id) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        // Ambil absensi siswa untuk jadwal dan tanggal yang sama
        $absensiSiswa = Absensi::where('jadwal_id', $absensiGuru->jadwal_id)
            ->where('tanggal', $absensiGuru->tanggal)
            ->with(['siswa'])
            ->get();

        return view('guru.absensi-guru.show', compact('absensiGuru', 'absensiSiswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AbsensiGuru $absensiGuru)
    {
        $guru = Auth::user()->guru;
        
        if (!$guru || $absensiGuru->guru_id !== $guru->id) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        // Ambil siswa dari kelas yang sesuai
        $siswaList = Siswa::where('kelas_id', $absensiGuru->jadwal->kelas_id)
            ->orderBy('nama')
            ->get();

        // Ambil absensi siswa yang sudah ada
        $absensiSiswa = Absensi::where('jadwal_id', $absensiGuru->jadwal_id)
            ->where('tanggal', $absensiGuru->tanggal)
            ->with(['siswa'])
            ->get()
            ->keyBy('siswa_id');

        return view('guru.absensi-guru.edit', compact('absensiGuru', 'siswaList', 'absensiSiswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AbsensiGuru $absensiGuru)
    {
        $guru = Auth::user()->guru;
        
        if (!$guru || $absensiGuru->guru_id !== $guru->id) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'status' => 'required|in:Hadir,Izin,Sakit,Tidak KBM,Tugas',
            'keterangan' => 'nullable|string',
            'materi_yang_diajarkan' => 'nullable|string',
            'catatan_kbm' => 'nullable|string',
        ]);

        $absensiGuru->update([
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'materi_yang_diajarkan' => $request->materi_yang_diajarkan,
        ]);

        // Update absensi siswa jika ada
        if ($request->has('siswa_absensi')) {
            foreach ($request->siswa_absensi as $siswaId => $data) {
                if (isset($data['status']) && $data['status'] !== '') {
                    $absensiSiswa = Absensi::where('jadwal_id', $absensiGuru->jadwal_id)
                        ->where('siswa_id', $siswaId)
                        ->where('tanggal', $absensiGuru->tanggal)
                        ->first();

                    if ($absensiSiswa) {
                        $absensiSiswa->update([
                            'status' => $data['status'],
                            'keterangan' => $data['keterangan'] ?? null,
                        ]);
                    } else {
                        // Buat absensi baru jika belum ada
                        Absensi::create([
                            'jadwal_id' => $absensiGuru->jadwal_id,
                            'siswa_id' => $siswaId,
                            'tanggal' => $absensiGuru->tanggal,
                            'status' => $data['status'],
                            'keterangan' => $data['keterangan'] ?? null,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('absensi-guru.index')->with('success', 'Absensi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AbsensiGuru $absensiGuru)
    {
        $guru = Auth::user()->guru;
        
        if (!$guru || $absensiGuru->guru_id !== $guru->id) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        // Hapus absensi siswa yang terkait
        Absensi::where('jadwal_id', $absensiGuru->jadwal_id)
            ->where('tanggal', $absensiGuru->tanggal)
            ->delete();

        $absensiGuru->delete();

        return redirect()->route('absensi-guru.index')->with('success', 'Absensi berhasil dihapus.');
    }

    /**
     * Get jadwal mengajar untuk guru
     */
    public function getJadwalMengajar()
    {
        $guru = Auth::user()->guru;
        
        if (!$guru) {
            return response()->json(['error' => 'Data guru tidak ditemukan.'], 404);
        }

        $jadwalMengajar = Jadwal::where('guru_id', $guru->id)
            ->with(['kelas', 'guru'])
            ->get();

        return response()->json($jadwalMengajar);
    }

    /**
     * Get siswa berdasarkan jadwal
     */
    public function getSiswaByJadwal(Request $request)
    {
        $jadwalId = $request->jadwal_id;
        $jadwal = Jadwal::with('kelas.siswas')->find($jadwalId);
        
        if (!$jadwal) {
            return response()->json(['siswas' => []]);
        }

        $siswas = $jadwal->kelas->siswas;
        return response()->json(['siswas' => $siswas]);
    }

    /**
     * Convert English day to Indonesian day
     */
    private function getHariIndonesia($hariInggris)
    {
        $hariMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        return $hariMap[$hariInggris] ?? $hariInggris;
    }
}
