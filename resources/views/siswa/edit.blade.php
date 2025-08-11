@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 max-w-lg">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Edit Siswa</h2>
        <form action="{{ route('siswa.update', $siswa) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block font-semibold mb-1">Foto (opsional)</label>
                @if($siswa->foto)
                    <img src="{{ asset('storage/'.$siswa->foto) }}" alt="foto" class="h-14 w-14 rounded-full object-cover mb-2">
                @endif
                <input type="file" name="foto" class="border rounded px-3 py-2 w-full">
                @error('foto')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="block font-semibold mb-1">NIS</label>
                <input type="text" name="nis" value="{{ old('nis', $siswa->nis) }}" class="border rounded px-3 py-2 w-full">
                @error('nis')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="block font-semibold mb-1">Nama</label>
                <input type="text" name="nama" value="{{ old('nama', $siswa->nama) }}" class="border rounded px-3 py-2 w-full">
                @error('nama')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="block font-semibold mb-1">Kelas</label>
                <select name="kelas_id" class="border rounded px-3 py-2 w-full">
                    <option value="">Pilih Kelas</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" @selected(old('kelas_id', $siswa->kelas_id) == $kelas->id)>{{ $kelas->nama }}</option>
                    @endforeach
                </select>
                @error('kelas_id')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="block font-semibold mb-1">Jurusan</label>
                <input type="text" name="jurusan" value="{{ old('jurusan', $siswa->jurusan) }}" class="border rounded px-3 py-2 w-full">
                @error('jurusan')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="block font-semibold mb-1">Alamat</label>
                <textarea name="alamat" class="border rounded px-3 py-2 w-full">{{ old('alamat', $siswa->alamat) }}</textarea>
                @error('alamat')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="block font-semibold mb-1">No HP</label>
                <input type="text" name="no_hp" value="{{ old('no_hp', $siswa->no_hp) }}" class="border rounded px-3 py-2 w-full">
                @error('no_hp')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
            </div>
            <div class="flex gap-2 mt-4">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"><i class="fa fa-save mr-1"></i>Simpan</button>
                <a href="{{ route('siswa.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Batal</a>
            </div>
        </form>
    </div>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
@endsection 