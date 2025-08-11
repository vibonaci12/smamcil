<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Jadwal;
use App\Models\Absensi;
use App\Models\AbsensiGuru;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();
        
        if (!$guru) {
            return redirect()->route('dashboard')->with('error', 'Data guru tidak ditemukan.');
        }

        // Ambil semua jadwal yang diajar guru login
        $jadwalIds = Jadwal::where('guru_id', $guru->id)->pluck('id');
        
        // Ambil absensi untuk jadwal tersebut
        $absensis = Absensi::whereIn('jadwal_id', $jadwalIds)
            ->with(['jadwal.kelas', 'siswa'])
            ->orderBy('tanggal', 'desc')
            ->paginate(15);
            
        return view('guru.absensi.index', compact('absensis'));
    }

    public function create()
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();
        
        if (!$guru) {
            return redirect()->route('dashboard')->with('error', 'Data guru tidak ditemukan.');
        }

        // Ambil jadwal yang diajar guru hari ini
        $hariIni = \Carbon\Carbon::now()->format('l');
        $hariIndonesia = $this->getHariIndonesia($hariIni);
        
        $jadwals = Jadwal::where('guru_id', $guru->id)
            ->where('hari', $hariIndonesia)
            ->with(['kelas'])
            ->get();
            
        return view('guru.absensi.create', compact('jadwals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'tanggal' => 'required|date',
            'status_guru' => 'required|in:Hadir,Izin,Sakit,Tidak KBM,Tugas',
            'keterangan_guru' => 'nullable|string',
            'materi_yang_diajarkan' => 'nullable|string',
            'catatan_kbm' => 'nullable|string',
        ]);

        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();
        
        if (!$guru) {
            return redirect()->route('dashboard')->with('error', 'Data guru tidak ditemukan.');
        }

        // Cek apakah jadwal sesuai dengan guru yang login
        $jadwal = Jadwal::where('id', $request->jadwal_id)
            ->where('guru_id', $guru->id)
            ->first();

        if (!$jadwal) {
            return back()->withErrors(['error' => 'Jadwal tidak ditemukan atau tidak sesuai.'])->withInput();
        }

        // Cek apakah tanggal absensi sesuai dengan hari jadwal
        $hariJadwal = $jadwal->hari;
        $hariAbsensi = date('l', strtotime($request->tanggal));
        $hariIndonesia = $this->getHariIndonesia($hariAbsensi);

        if ($hariIndonesia !== $hariJadwal) {
            return back()->withErrors(['error' => 'Tanggal absensi tidak sesuai dengan hari jadwal.'])->withInput();
        }

        // Cek apakah sudah ada absensi guru untuk jadwal dan tanggal yang sama
        $existingAbsensiGuru = AbsensiGuru::where('guru_id', $guru->id)
            ->where('jadwal_id', $request->jadwal_id)
            ->where('tanggal', $request->tanggal)
            ->first();

        if ($existingAbsensiGuru) {
            return back()->withErrors(['error' => 'Absensi untuk jadwal ini pada tanggal tersebut sudah ada.'])->withInput();
        }

        // Simpan absensi guru
        $absensiGuru = AbsensiGuru::create([
            'guru_id' => $guru->id,
            'jadwal_id' => $request->jadwal_id,
            'tanggal' => $request->tanggal,
            'status' => $request->status_guru,
            'keterangan' => $request->keterangan_guru,
            'materi_yang_diajarkan' => $request->materi_yang_diajarkan,
            'catatan_kbm' => $request->catatan_kbm,
        ]);

        // Simpan absensi siswa jika ada
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

        return redirect()->route('absensi.index')
            ->with('success', 'Absensi berhasil ditambahkan!');
    }

    public function show(Absensi $absensi)
    {
        return view('guru.absensi.show', compact('absensi'));
    }

    public function edit(Absensi $absensi)
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();
        
        if (!$guru) {
            return redirect()->route('dashboard')->with('error', 'Data guru tidak ditemukan.');
        }

        // Ambil jadwal yang diajar guru
        $jadwals = Jadwal::where('guru_id', $guru->id)
            ->with(['kelas'])
            ->get();
            
        return view('guru.absensi.edit', compact('absensi', 'jadwals'));
    }

    public function update(Request $request, Absensi $absensi)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:Hadir,Izin,Sakit,Alpha',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Cek apakah sudah ada absensi lain untuk siswa, jadwal, dan tanggal yang sama
        $existingAbsensi = Absensi::where('jadwal_id', $request->jadwal_id)
            ->where('siswa_id', $request->siswa_id)
            ->where('tanggal', $request->tanggal)
            ->where('id', '!=', $absensi->id)
            ->first();

        if ($existingAbsensi) {
            return back()->withErrors(['error' => 'Absensi untuk siswa ini pada tanggal tersebut sudah ada.'])->withInput();
        }

        $absensi->update($request->all());

        return redirect()->route('absensi.index')
            ->with('success', 'Absensi berhasil diperbarui!');
    }

    public function destroy(Absensi $absensi)
    {
        $absensi->delete();

        return redirect()->route('absensi.index')
            ->with('success', 'Absensi berhasil dihapus!');
    }

    // Method untuk mendapatkan siswa berdasarkan jadwal
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
