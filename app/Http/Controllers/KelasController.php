<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $query = Kelas::with('waliKelas');
        if ($request->filled('q')) {
            $query->where('nama', 'like', '%'.$request->q.'%');
        }
        $kelas = $query->orderBy('nama')->paginate(10);
        $guruList = Guru::orderBy('nama')->get();
        return view('kelas.index', compact('kelas', 'guruList'));
    }

    public function create()
    {
        $guruList = Guru::orderBy('nama')->get();
        return view('kelas.create', compact('guruList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|unique:kelas,nama',
            'wali_kelas_id' => 'nullable|exists:gurus,id',
            'jurusan' => 'nullable|string|max:100',
        ]);
        Kelas::create($validated);
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kelas)
    {
        $guruList = Guru::orderBy('nama')->get();
        return view('kelas.edit', compact('kelas', 'guruList'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $validated = $request->validate([
            'nama' => 'required|unique:kelas,nama,'.$kelas->id,
            'wali_kelas_id' => 'nullable|exists:gurus,id',
            'jurusan' => 'nullable|string|max:100',
        ]);
        $kelas->update($validated);
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diupdate.');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}
