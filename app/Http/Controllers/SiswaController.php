<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with(['kelas', 'user']); // Include user relationship
        if ($request->filled('q')) {
            $query->where('nama', 'like', '%'.$request->q.'%');
        }
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }
        $siswas = $query->orderBy('nama')->paginate(10);
        $kelasList = Kelas::all();
        return view('siswa.index', compact('siswas', 'kelasList'));
    }

    public function create()
    {
        $kelasList = Kelas::all();
        return view('siswa.create', compact('kelasList'));
    }

    public function store(Request $request)
    {
        // Debug: Log the received NIS value
        \Log::info('Received NIS: "' . $request->nis . '" (length: ' . strlen($request->nis) . ')');
        
        $validated = $request->validate([
            'nis' => 'required|digits:8|unique:siswas,nis|unique:users,username',
            'nama' => 'required',
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal_lahir' => 'required|date',
            'jurusan' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'foto' => 'nullable|image|max:2048',
        ], [
            'nis.digits' => 'NIS harus tepat 8 digit angka (contoh: 12345678)',
            'nis.unique' => 'NIS sudah terdaftar, gunakan NIS yang berbeda',
        ]);
        
        // Use database transaction to ensure data consistency
        return \DB::transaction(function () use ($request, $validated) {
            if ($request->hasFile('foto')) {
                $validated['foto'] = $request->file('foto')->store('foto_siswa', 'public');
            }
            
            $password = date('Ymd', strtotime($validated['tanggal_lahir']));
            
            // Create user account first
            $user = \App\Models\User::create([
                'name' => $validated['nama'],
                'username' => $validated['nis'],
                'role' => 'siswa',
                'password' => bcrypt($password),
            ]);
            
            // Create siswa record with user_id
            $validated['user_id'] = $user->id;
            Siswa::create($validated);
            
            return redirect()->route('siswa.index')->with('success', 'Siswa & akun berhasil ditambahkan. Password awal: ' . $password);
        });
    }

    public function edit(Siswa $siswa)
    {
        $kelasList = Kelas::all();
        return view('siswa.edit', compact('siswa', 'kelasList'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nis' => 'required|unique:siswas,nis,'.$siswa->id,
            'nama' => 'required',
            'kelas_id' => 'required|exists:kelas,id',
            'jurusan' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'foto' => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('foto')) {
            if ($siswa->foto) Storage::disk('public')->delete($siswa->foto);
            $validated['foto'] = $request->file('foto')->store('foto_siswa', 'public');
        }
        $siswa->update($validated);
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil diupdate.');
    }

    public function destroy(Siswa $siswa)
    {
        // Delete associated user account
        if ($siswa->user) {
            $siswa->user->delete();
        }
        
        // Delete siswa photo if exists
        if ($siswa->foto) {
            Storage::disk('public')->delete($siswa->foto);
        }
        
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Siswa & akun berhasil dihapus.');
    }
}
