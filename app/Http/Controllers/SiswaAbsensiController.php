<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaAbsensiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        
        if (!$siswa) {
            return redirect()->route('dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }

        // Ambil absensi untuk siswa
        $absensis = Absensi::where('siswa_id', $siswa->id)
            ->with(['jadwal.guru', 'jadwal.kelas'])
            ->orderBy('tanggal', 'desc')
            ->paginate(15);

        return view('siswa.absensi.index', compact('absensis'));
    }
}
