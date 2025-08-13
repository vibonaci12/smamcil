<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Jadwal;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\JenisPenilaian;
use App\Services\GuruService;
use App\Services\NilaiService;
use App\Http\Requests\Nilai\StoreBatchRequest;
use App\Http\Requests\Nilai\UpdateBatchRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    public function index()
    {
        try {
            $guru = GuruService::getAuthenticatedGuru();
            
            // Get all schedules taught by the logged-in teacher
            $jadwalIds = GuruService::getJadwalIds();
            
            // Get grades for those schedules with eager loading
            $nilais = Nilai::whereIn('jadwal_id', $jadwalIds)
                ->with(['jadwal.kelas', 'siswa', 'jenisPenilaian'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
                
            return view('guru.nilai.index', compact('nilais'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', $e->getMessage());
        }
    }

    // Show selection page for batch input
    public function select()
    {
        try {
            $guru = GuruService::getAuthenticatedGuru();

            // Get schedules taught by the teacher with eager loading
            $jadwals = $guru->jadwals()
                ->with(['kelas'])
                ->orderBy('kelas_id')
                ->orderBy('hari')
                ->orderBy('jam_mulai')
                ->get();
                
            // Get all assessment types
            $jenisPenilaians = JenisPenilaian::orderBy('nama')->get();
                
            return view('guru.nilai.select', compact('jadwals', 'jenisPenilaians'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', $e->getMessage());
        }
    }

    // Show batch input form
    public function createBatch(Request $request)
    {
        try {
            $request->validate([
                'jadwal_id' => 'required|exists:jadwals,id',
                'jenis_penilaian_id' => 'required|exists:jenis_penilaians,id',
            ]);

            // Verify the schedule belongs to the teacher
            $jadwal = GuruService::verifyJadwalOwnership($request->jadwal_id);
            
            if (!$jadwal) {
                return redirect()->route('nilai.select')->with('error', 'Jadwal tidak ditemukan atau tidak dapat diakses.');
            }

            // Load the schedule with students
            $jadwal->load(['kelas.siswas']);

            $jenisPenilaian = JenisPenilaian::find($request->jenis_penilaian_id);
            
            // Get existing grades for this schedule and assessment type with eager loading
            $existingNilai = Nilai::where('jadwal_id', $request->jadwal_id)
                ->where('jenis_penilaian_id', $request->jenis_penilaian_id)
                ->with(['siswa'])
                ->get()
                ->keyBy('siswa_id');

            return view('guru.nilai.create-batch', compact('jadwal', 'jenisPenilaian', 'existingNilai'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', $e->getMessage());
        }
    }

    // Store batch grades
    public function storeBatch(StoreBatchRequest $request)
    {
        try {
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
        } catch (\Exception $e) {
            return redirect()->route('nilai.select')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Show batch edit form
    public function editBatch(Request $request)
    {
        try {
            $request->validate([
                'jadwal_id' => 'required|exists:jadwals,id',
                'jenis_penilaian_id' => 'required|exists:jenis_penilaians,id',
            ]);

            // Verify the schedule belongs to the teacher
            $jadwal = GuruService::verifyJadwalOwnership($request->jadwal_id);
            
            if (!$jadwal) {
                return redirect()->route('nilai.index')->with('error', 'Jadwal tidak ditemukan atau tidak dapat diakses.');
            }

            // Load the schedule with students
            $jadwal->load(['kelas.siswas']);

            $jenisPenilaian = JenisPenilaian::find($request->jenis_penilaian_id);
            
            // Get existing grades for this schedule and assessment type with eager loading
            $existingNilai = Nilai::where('jadwal_id', $request->jadwal_id)
                ->where('jenis_penilaian_id', $request->jenis_penilaian_id)
                ->with(['siswa'])
                ->get()
                ->keyBy('siswa_id');

            return view('guru.nilai.edit-batch', compact('jadwal', 'jenisPenilaian', 'existingNilai'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', $e->getMessage());
        }
    }

    // Update batch grades
    public function updateBatch(UpdateBatchRequest $request)
    {
        try {
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
        } catch (\Exception $e) {
            return redirect()->route('nilai.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Get students by schedule (for AJAX)
    public function getSiswaByJadwal(Request $request)
    {
        try {
            $request->validate([
                'jadwal_id' => 'required|exists:jadwals,id',
            ]);

            $jadwal = GuruService::verifyJadwalOwnership($request->jadwal_id);
            
            if (!$jadwal) {
                return response()->json(['error' => 'Jadwal tidak ditemukan atau tidak dapat diakses.'], 404);
            }

            $jadwal->load('kelas.siswas');
            $siswas = $jadwal->kelas->siswas;
            
            return response()->json(['siswas' => $siswas]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }

    /**
     * Get transcript for a student
     *
     * @param int $siswaId
     * @return \Illuminate\Http\Response
     */
    public function transkripSiswa($siswaId)
    {
        try {
            $transkrip = NilaiService::getTranskripSiswa($siswaId);
            
            return view('guru.nilai.transkrip', compact('transkrip'));
        } catch (\Exception $e) {
            return redirect()->route('nilai.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Get final grades for a class
     *
     * @param int $kelasId
     * @return \Illuminate\Http\Response
     */
    public function nilaiAkhirKelas($kelasId)
    {
        try {
            $guru = GuruService::getAuthenticatedGuru();
            
            // Verify the class is taught by the authenticated teacher
            $jadwals = $guru->jadwals()->where('kelas_id', $kelasId)->pluck('id');
            
            if ($jadwals->isEmpty()) {
                return redirect()->route('nilai.index')->with('error', 'Kelas tidak ditemukan atau tidak dapat diakses.');
            }

            $siswas = Siswa::where('kelas_id', $kelasId)
                ->with(['nilais' => function($query) use ($jadwals) {
                    $query->whereIn('jadwal_id', $jadwals);
                }, 'nilais.jadwal', 'nilais.jenisPenilaian'])
                ->get();

            $nilaiAkhir = [];
            foreach ($siswas as $siswa) {
                $nilaiAkhir[$siswa->id] = [];
                foreach ($jadwals as $jadwalId) {
                    $nilaiAkhir[$siswa->id][$jadwalId] = NilaiService::hitungNilaiAkhir($siswa->id, $jadwalId);
                }
            }

            $kelas = $siswas->first()->kelas ?? null;
            
            return view('guru.nilai.nilai-akhir-kelas', compact('siswas', 'nilaiAkhir', 'kelas', 'jadwals'));
        } catch (\Exception $e) {
            return redirect()->route('nilai.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Get classes taught by the authenticated teacher
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getKelasGuru()
    {
        try {
            $guru = GuruService::getAuthenticatedGuru();
            
            $kelas = $guru->jadwals()
                ->with(['kelas'])
                ->get()
                ->pluck('kelas')
                ->unique('id')
                ->values();
            
            return response()->json(['kelas' => $kelas]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }

    /**
     * Get students taught by the authenticated teacher
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSiswaGuru()
    {
        try {
            $guru = GuruService::getAuthenticatedGuru();
            
            $jadwalIds = $guru->jadwals()->pluck('id');
            
            $siswa = Siswa::whereHas('kelas.jadwals', function($query) use ($jadwalIds) {
                $query->whereIn('id', $jadwalIds);
            })
            ->with(['kelas'])
            ->get();
            
            return response()->json(['siswa' => $siswa]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }
}
