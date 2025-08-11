<?php

namespace App\Http\Controllers;

use App\Models\JenisPenilaian;
use Illuminate\Http\Request;

class JenisPenilaianController extends Controller
{
    public function index()
    {
        $jenisPenilaians = JenisPenilaian::orderBy('nama')->paginate(15);
        return view('admin.jenis-penilaian.index', compact('jenisPenilaians'));
    }

    public function create()
    {
        return view('admin.jenis-penilaian.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:jenis_penilaians,nama',
            'bobot' => 'required|numeric|min:0|max:100',
        ], [
            'nama.required' => 'Nama jenis penilaian harus diisi.',
            'nama.unique' => 'Nama jenis penilaian sudah ada.',
            'bobot.required' => 'Bobot harus diisi.',
            'bobot.min' => 'Bobot minimal 0%.',
            'bobot.max' => 'Bobot maksimal 100%.',
        ]);

        JenisPenilaian::create($validated);

        return redirect()->route('jenis-penilaian.index')
            ->with('success', 'Jenis penilaian berhasil ditambahkan!');
    }

    public function show(JenisPenilaian $jenisPenilaian)
    {
        return view('admin.jenis-penilaian.show', compact('jenisPenilaian'));
    }

    public function edit(JenisPenilaian $jenisPenilaian)
    {
        return view('admin.jenis-penilaian.edit', compact('jenisPenilaian'));
    }

    public function update(Request $request, JenisPenilaian $jenisPenilaian)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:jenis_penilaians,nama,' . $jenisPenilaian->id,
            'bobot' => 'required|numeric|min:0|max:100',
        ], [
            'nama.required' => 'Nama jenis penilaian harus diisi.',
            'nama.unique' => 'Nama jenis penilaian sudah ada.',
            'bobot.required' => 'Bobot harus diisi.',
            'bobot.min' => 'Bobot minimal 0%.',
            'bobot.max' => 'Bobot maksimal 100%.',
        ]);

        $jenisPenilaian->update($validated);

        return redirect()->route('jenis-penilaian.index')
            ->with('success', 'Jenis penilaian berhasil diperbarui!');
    }

    public function destroy(JenisPenilaian $jenisPenilaian)
    {
        // Check if jenis penilaian is being used
        if ($jenisPenilaian->nilais()->count() > 0) {
            return back()->withErrors(['error' => 'Jenis penilaian tidak dapat dihapus karena masih digunakan dalam data nilai.']);
        }

        $jenisPenilaian->delete();

        return redirect()->route('jenis-penilaian.index')
            ->with('success', 'Jenis penilaian berhasil dihapus!');
    }
}
