@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm mx-auto" style="max-width: 600px;">
        <div class="card-body">
            <h3 class="card-title mb-4 fw-bold">Tambah Siswa</h3>
            <form action="{{ route('siswa.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Foto (opsional)</label>
                    <input type="file" name="foto" class="form-control">
                    @error('foto')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">NIS</label>
                    <input type="text" name="nis" value="{{ old('nis') }}" class="form-control">
                    @error('nis')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" class="form-control">
                    @error('nama')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kelas</label>
                    <select name="kelas_id" class="form-select">
                        <option value="">Pilih Kelas</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" @selected(old('kelas_id') == $kelas->id)>{{ $kelas->nama }}</option>
                        @endforeach
                    </select>
                    @error('kelas_id')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Jurusan</label>
                    <input type="text" name="jurusan" value="{{ old('jurusan') }}" class="form-control">
                    @error('jurusan')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3">{{ old('alamat') }}</textarea>
                    @error('alamat')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">No HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="form-control">
                    @error('no_hp')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                    <a href="{{ route('siswa.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
