<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $query = Guru::with('user'); // Include user relationship
        if ($request->filled('q')) {
            $query->where('nama', 'like', '%'.$request->q.'%');
        }
        if ($request->filled('mapel')) {
            $query->where('mapel', $request->mapel);
        }
        $gurus = $query->orderBy('nama')->paginate(10);
        $mapelList = Guru::select('mapel')->distinct()->pluck('mapel');
        return view('guru.index', compact('gurus', 'mapelList'));
    }

    public function create()
    {
        return view('guru.create');
    }

    public function store(Request $request)
    {
        // Debug: Log the received NIP value
        \Log::info('Received NIP: "' . $request->nip . '" (length: ' . strlen($request->nip) . ')');
        
        $validated = $request->validate([
            'nip' => 'required|digits:8|unique:gurus,nip|unique:users,username',
            'nama' => 'required',
            'mapel' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'foto' => 'nullable|image|max:2048',
        ], [
            'nip.digits' => 'NIP harus tepat 8 digit angka (contoh: 12345678)',
            'nip.unique' => 'NIP sudah terdaftar, gunakan NIP yang berbeda',
        ]);
        
        // Use database transaction to ensure data consistency
        return \DB::transaction(function () use ($request, $validated) {
            if ($request->hasFile('foto')) {
                $validated['foto'] = $request->file('foto')->store('foto_guru', 'public');
            }
            
            $password = date('Ymd', strtotime($validated['tanggal_lahir']));
            
            // Create user account first
            $user = \App\Models\User::create([
                'name' => $validated['nama'],
                'username' => $validated['nip'],
                'role' => 'guru',
                'password' => bcrypt($password),
            ]);
            
            // Create guru record with user_id
            $validated['user_id'] = $user->id;
            Guru::create($validated);
            
            return redirect()->route('guru.index')->with('success', 'Guru & akun berhasil ditambahkan. Password awal: ' . $password);
        });
    }

    public function edit(Guru $guru)
    {
        return view('guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $validated = $request->validate([
            'nip' => 'required|unique:gurus,nip,'.$guru->id,
            'nama' => 'required',
            'mapel' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'foto' => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('foto')) {
            if ($guru->foto) Storage::disk('public')->delete($guru->foto);
            $validated['foto'] = $request->file('foto')->store('foto_guru', 'public');
        }
        $guru->update($validated);
        return redirect()->route('guru.index')->with('success', 'Guru berhasil diupdate.');
    }

    public function destroy(Guru $guru)
    {
        // Delete associated user account
        if ($guru->user) {
            $guru->user->delete();
        }
        
        // Delete guru photo if exists
        if ($guru->foto) {
            Storage::disk('public')->delete($guru->foto);
        }
        
        $guru->delete();
        return redirect()->route('guru.index')->with('success', 'Guru & akun berhasil dihapus.');
    }
}
