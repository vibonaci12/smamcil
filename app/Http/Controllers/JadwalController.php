<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Jika user adalah guru, hanya tampilkan jadwal mengajar mereka
        if ($user->role === 'guru') {
            $guru = Guru::where('user_id', $user->id)->first();
            if ($guru) {
                $query = Jadwal::where('guru_id', $guru->id)->with(['kelas', 'guru']);
                
                // Filter by kelas
                if ($request->filled('kelas_id')) {
                    $query->where('kelas_id', $request->kelas_id);
                }
                
                // Filter by hari
                if ($request->filled('hari')) {
                    $query->where('hari', $request->hari);
                }
                
                // Search by mata pelajaran
                if ($request->filled('q')) {
                    $query->where('mapel', 'like', '%' . $request->q . '%');
                }
                
                $jadwals = $query->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
                    ->orderBy('jam_mulai')
                    ->paginate(15);
                    
                $kelasList = Kelas::orderBy('nama')->get();
                $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
                return view('jadwal.index', compact('jadwals', 'kelasList', 'hariList'));
            }
        }
        
        // Untuk admin, tampilkan semua jadwal
        $query = Jadwal::with(['kelas', 'guru']);
        
        // Filter by kelas
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }
        
        // Filter by guru
        if ($request->filled('guru_id')) {
            $query->where('guru_id', $request->guru_id);
        }
        
        // Filter by hari
        if ($request->filled('hari')) {
            $query->where('hari', $request->hari);
        }
        
        // Search by mata pelajaran
        if ($request->filled('q')) {
            $query->where('mapel', 'like', '%' . $request->q . '%');
        }
        
        $jadwals = $query->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
            ->orderBy('jam_mulai')
            ->paginate(15);
            
        $kelasList = Kelas::orderBy('nama')->get();
        $guruList = Guru::orderBy('nama')->get();
        $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        return view('jadwal.index', compact('jadwals', 'kelasList', 'guruList', 'hariList'));
    }

    public function create()
    {
        $kelasList = Kelas::orderBy('nama')->get();
        $guruList = Guru::orderBy('nama')->get();
        $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        return view('jadwal.create', compact('kelasList', 'guruList', 'hariList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'guru_id' => 'required|exists:gurus,id',
            'mapel' => 'required|string|max:255',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ], [
            'kelas_id.required' => 'Kelas harus dipilih.',
            'guru_id.required' => 'Guru harus dipilih.',
            'mapel.required' => 'Mata pelajaran harus diisi.',
            'hari.required' => 'Hari harus dipilih.',
            'jam_mulai.required' => 'Jam mulai harus diisi.',
            'jam_mulai.date_format' => 'Format jam mulai harus 24 jam (contoh: 07:00, 13:30).',
            'jam_selesai.required' => 'Jam selesai harus diisi.',
            'jam_selesai.date_format' => 'Format jam selesai harus 24 jam (contoh: 08:30, 14:30).',
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
        ]);

        // Check for schedule conflicts
        $conflict = Jadwal::where('kelas_id', $validated['kelas_id'])
            ->where('hari', $validated['hari'])
            ->where(function($query) use ($validated) {
                $query->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                    ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                    ->orWhere(function($q) use ($validated) {
                        $q->where('jam_mulai', '<=', $validated['jam_mulai'])
                            ->where('jam_selesai', '>=', $validated['jam_selesai']);
                    });
            })
            ->first();

        if ($conflict) {
            return back()->withErrors(['conflict' => 'Jadwal bentrok dengan jadwal yang sudah ada pada hari yang sama.'])->withInput();
        }

        Jadwal::create($validated);
        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(Jadwal $jadwal)
    {
        $kelasList = Kelas::orderBy('nama')->get();
        $guruList = Guru::orderBy('nama')->get();
        $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        return view('jadwal.edit', compact('jadwal', 'kelasList', 'guruList', 'hariList'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'guru_id' => 'required|exists:gurus,id',
            'mapel' => 'required|string|max:255',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ], [
            'kelas_id.required' => 'Kelas harus dipilih.',
            'guru_id.required' => 'Guru harus dipilih.',
            'mapel.required' => 'Mata pelajaran harus diisi.',
            'hari.required' => 'Hari harus dipilih.',
            'jam_mulai.required' => 'Jam mulai harus diisi.',
            'jam_mulai.date_format' => 'Format jam mulai harus 24 jam (contoh: 07:00, 13:30).',
            'jam_selesai.required' => 'Jam selesai harus diisi.',
            'jam_selesai.date_format' => 'Format jam selesai harus 24 jam (contoh: 08:30, 14:30).',
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
        ]);

        // Check for schedule conflicts (excluding current jadwal)
        $conflict = Jadwal::where('id', '!=', $jadwal->id)
            ->where('kelas_id', $validated['kelas_id'])
            ->where('hari', $validated['hari'])
            ->where(function($query) use ($validated) {
                $query->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                    ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                    ->orWhere(function($q) use ($validated) {
                        $q->where('jam_mulai', '<=', $validated['jam_mulai'])
                            ->where('jam_selesai', '>=', $validated['jam_selesai']);
                    });
            })
            ->first();

        if ($conflict) {
            return back()->withErrors(['conflict' => 'Jadwal bentrok dengan jadwal yang sudah ada pada hari yang sama.'])->withInput();
        }

        $jadwal->update($validated);
        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diupdate.');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }

    // Method untuk guru melihat jadwal mengajar
    public function jadwalMengajar()
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();
        
        if (!$guru) {
            return redirect()->route('dashboard')->with('error', 'Data guru tidak ditemukan.');
        }

        $jadwals = Jadwal::where('guru_id', $guru->id)
            ->with(['kelas'])
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam_mulai')
            ->get();

        return view('guru.jadwal-mengajar', compact('jadwals'));
    }
}
