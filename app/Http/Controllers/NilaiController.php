<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Jadwal;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
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
        
        // Ambil nilai untuk jadwal tersebut
        $nilais = Nilai::whereIn('jadwal_id', $jadwalIds)
            ->with(['jadwal.kelas', 'siswa'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('guru.nilai.index', compact('nilais'));
    }

    public function create()
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
            
        return view('guru.nilai.create', compact('jadwals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'siswa_id' => 'required|exists:siswas,id',
            'jenis' => 'required|in:Tugas,UTS,UAS',
            'nilai' => 'required|numeric|min:0|max:100',
        ]);

        // Cek apakah sudah ada nilai untuk siswa, jadwal, dan jenis yang sama
        $existingNilai = Nilai::where('jadwal_id', $request->jadwal_id)
            ->where('siswa_id', $request->siswa_id)
            ->where('jenis', $request->jenis)
            ->first();

        if ($existingNilai) {
            return back()->withErrors(['error' => 'Nilai untuk siswa ini pada jenis tersebut sudah ada.'])->withInput();
        }

        Nilai::create($request->all());

        return redirect()->route('nilai.index')
            ->with('success', 'Nilai berhasil ditambahkan!');
    }

    public function show(Nilai $nilai)
    {
        return view('guru.nilai.show', compact('nilai'));
    }

    public function edit(Nilai $nilai)
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
            
        return view('guru.nilai.edit', compact('nilai', 'jadwals'));
    }

    public function update(Request $request, Nilai $nilai)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'siswa_id' => 'required|exists:siswas,id',
            'jenis' => 'required|in:Tugas,UTS,UAS',
            'nilai' => 'required|numeric|min:0|max:100',
        ]);

        // Cek apakah sudah ada nilai lain untuk siswa, jadwal, dan jenis yang sama
        $existingNilai = Nilai::where('jadwal_id', $request->jadwal_id)
            ->where('siswa_id', $request->siswa_id)
            ->where('jenis', $request->jenis)
            ->where('id', '!=', $nilai->id)
            ->first();

        if ($existingNilai) {
            return back()->withErrors(['error' => 'Nilai untuk siswa ini pada jenis tersebut sudah ada.'])->withInput();
        }

        $nilai->update($request->all());

        return redirect()->route('nilai.index')
            ->with('success', 'Nilai berhasil diperbarui!');
    }

    public function destroy(Nilai $nilai)
    {
        $nilai->delete();

        return redirect()->route('nilai.index')
            ->with('success', 'Nilai berhasil dihapus!');
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
}
