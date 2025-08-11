<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Siswa;
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

        // Ambil nilai untuk siswa
        $nilais = Nilai::where('siswa_id', $siswa->id)
            ->with(['jadwal.guru', 'jadwal.kelas'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('siswa.nilai.index', compact('nilais'));
    }
}
