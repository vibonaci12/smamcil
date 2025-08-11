<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Jadwal;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\JenisPenilaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Check if user is guru
        if ($user->role !== 'guru') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Hanya guru yang dapat mengakses halaman ini.');
        }

        $guru = Guru::where('user_id', $user->id)->first();
        
        if (!$guru) {
            return redirect()->route('dashboard')->with('error', 'Data guru tidak ditemukan.');
        }

        // Get all schedules taught by the logged-in teacher
        $jadwalIds = Jadwal::where('guru_id', $guru->id)->pluck('id');
        
        // Get grades for those schedules
        $nilais = Nilai::whereIn('jadwal_id', $jadwalIds)
            ->with(['jadwal.kelas', 'siswa', 'jenisPenilaian'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('guru.nilai.index', compact('nilais'));
    }

    // Show selection page for batch input
    public function select()
    {
        $user = Auth::user();
        
        if ($user->role !== 'guru') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $guru = Guru::where('user_id', $user->id)->first();
        
        if (!$guru) {
            return redirect()->route('dashboard')->with('error', 'Data guru tidak ditemukan.');
        }

        // Get schedules taught by the teacher
        $jadwals = Jadwal::where('guru_id', $guru->id)
            ->with(['kelas'])
            ->orderBy('kelas_id')
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();
            
        // Get all assessment types
        $jenisPenilaians = JenisPenilaian::orderBy('nama')->get();
            
        return view('guru.nilai.select', compact('jadwals', 'jenisPenilaians'));
    }

    // Show batch input form
    public function createBatch(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'guru') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'jenis_penilaian_id' => 'required|exists:jenis_penilaians,id',
        ]);

        $guru = Guru::where('user_id', $user->id)->first();
        
        if (!$guru) {
            return redirect()->route('dashboard')->with('error', 'Data guru tidak ditemukan.');
        }

        // Verify the schedule belongs to the teacher
        $jadwal = Jadwal::where('id', $request->jadwal_id)
            ->where('guru_id', $guru->id)
            ->with(['kelas.siswas'])
            ->first();

        if (!$jadwal) {
            return redirect()->route('nilai.select')->with('error', 'Jadwal tidak ditemukan atau tidak dapat diakses.');
        }

        $jenisPenilaian = JenisPenilaian::find($request->jenis_penilaian_id);
        
        // Get existing grades for this schedule and assessment type
        $existingNilai = Nilai::where('jadwal_id', $request->jadwal_id)
            ->where('jenis_penilaian_id', $request->jenis_penilaian_id)
            ->with(['siswa'])
            ->get()
            ->keyBy('siswa_id');

        return view('guru.nilai.create-batch', compact('jadwal', 'jenisPenilaian', 'existingNilai'));
    }

    // Store batch grades
    public function storeBatch(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'guru') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'jenis_penilaian_id' => 'required|exists:jenis_penilaians,id',
            'nilai' => 'required|array',
            'nilai.*' => 'nullable|numeric|min:0|max:100',
        ]);

        $guru = Guru::where('user_id', $user->id)->first();
        
        if (!$guru) {
            return redirect()->route('dashboard')->with('error', 'Data guru tidak ditemukan.');
        }

        // Verify the schedule belongs to the teacher
        $jadwal = Jadwal::where('id', $request->jadwal_id)
            ->where('guru_id', $guru->id)
            ->first();

        if (!$jadwal) {
            return redirect()->route('nilai.select')->with('error', 'Jadwal tidak ditemukan atau tidak dapat diakses.');
        }

        $successCount = 0;
        $updateCount = 0;

        foreach ($request->nilai as $siswaId => $nilaiValue) {
            if ($nilaiValue !== null && $nilaiValue !== '') {
                // Use updateOrCreate to handle both new and existing grades
                $nilai = Nilai::updateOrCreate(
                    [
                        'siswa_id' => $siswaId,
                        'jadwal_id' => $request->jadwal_id,
                        'jenis_penilaian_id' => $request->jenis_penilaian_id,
                    ],
                    [
                        'nilai' => $nilaiValue,
                    ]
                );

                if ($nilai->wasRecentlyCreated) {
                    $successCount++;
                } else {
                    $updateCount++;
                }
            }
        }

        $message = '';
        if ($successCount > 0) {
            $message .= "Berhasil menambahkan {$successCount} nilai baru. ";
        }
        if ($updateCount > 0) {
            $message .= "Berhasil memperbarui {$updateCount} nilai yang sudah ada. ";
        }

        return redirect()->route('nilai.index')
            ->with('success', $message ?: 'Tidak ada nilai yang disimpan.');
    }

    // Show batch edit form
    public function editBatch(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'guru') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'jenis_penilaian_id' => 'required|exists:jenis_penilaians,id',
        ]);

        $guru = Guru::where('user_id', $user->id)->first();
        
        if (!$guru) {
            return redirect()->route('dashboard')->with('error', 'Data guru tidak ditemukan.');
        }

        // Verify the schedule belongs to the teacher
        $jadwal = Jadwal::where('id', $request->jadwal_id)
            ->where('guru_id', $guru->id)
            ->with(['kelas.siswas'])
            ->first();

        if (!$jadwal) {
            return redirect()->route('nilai.index')->with('error', 'Jadwal tidak ditemukan atau tidak dapat diakses.');
        }

        $jenisPenilaian = JenisPenilaian::find($request->jenis_penilaian_id);
        
        // Get existing grades for this schedule and assessment type
        $existingNilai = Nilai::where('jadwal_id', $request->jadwal_id)
            ->where('jenis_penilaian_id', $request->jenis_penilaian_id)
            ->with(['siswa'])
            ->get()
            ->keyBy('siswa_id');

        return view('guru.nilai.edit-batch', compact('jadwal', 'jenisPenilaian', 'existingNilai'));
    }

    // Update batch grades
    public function updateBatch(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'guru') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'jenis_penilaian_id' => 'required|exists:jenis_penilaians,id',
            'nilai' => 'required|array',
            'nilai.*' => 'nullable|numeric|min:0|max:100',
        ]);

        $guru = Guru::where('user_id', $user->id)->first();
        
        if (!$guru) {
            return redirect()->route('dashboard')->with('error', 'Data guru tidak ditemukan.');
        }

        // Verify the schedule belongs to the teacher
        $jadwal = Jadwal::where('id', $request->jadwal_id)
            ->where('guru_id', $guru->id)
            ->first();

        if (!$jadwal) {
            return redirect()->route('nilai.index')->with('error', 'Jadwal tidak ditemukan atau tidak dapat diakses.');
        }

        $updateCount = 0;

        foreach ($request->nilai as $siswaId => $nilaiValue) {
            if ($nilaiValue !== null && $nilaiValue !== '') {
                // Update existing grade
                $nilai = Nilai::where('siswa_id', $siswaId)
                    ->where('jadwal_id', $request->jadwal_id)
                    ->where('jenis_penilaian_id', $request->jenis_penilaian_id)
                    ->first();

                if ($nilai) {
                    $nilai->update(['nilai' => $nilaiValue]);
                    $updateCount++;
                }
            }
        }

        return redirect()->route('nilai.index')
            ->with('success', "Berhasil memperbarui {$updateCount} nilai.");
    }

    // Get students by schedule (for AJAX)
    public function getSiswaByJadwal(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'guru') {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
        ]);

        $guru = Guru::where('user_id', $user->id)->first();
        
        if (!$guru) {
            return response()->json(['error' => 'Data guru tidak ditemukan.'], 404);
        }

        $jadwal = Jadwal::where('id', $request->jadwal_id)
            ->where('guru_id', $guru->id)
            ->with('kelas.siswas')
            ->first();
        
        if (!$jadwal) {
            return response()->json(['error' => 'Jadwal tidak ditemukan atau tidak dapat diakses.'], 404);
        }

        $siswas = $jadwal->kelas->siswas;
        return response()->json(['siswas' => $siswas]);
    }
}
