@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Judul Halaman -->
    <div class="p-4 rounded shadow-sm mb-4 bg-primary text-white d-flex justify-content-between align-items-center">
        <h1 class="fw-bold mb-0">
            <i class="bi bi-people-fill me-2"></i> Manajemen Siswa
        </h1>
        <button type="button" class="btn btn-light shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCreateSiswa">
            <i class="bi bi-plus-lg me-1"></i> Tambah Siswa
        </button>
    </div>

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filter dan Pencarian -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-funnel me-2 text-primary"></i> Filter & Pencarian
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-center">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                               placeholder="Cari nama siswa...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="kelas_id" class="form-select">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" @selected(request('kelas_id') == $kelas->id)>
                                {{ $kelas->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100"><i class="bi bi-search me-1"></i> Cari</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Siswa -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-table me-2 text-primary"></i> Data Siswa
            </h5>
            <span class="badge bg-primary">{{ $siswas->total() }} Siswa</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered align-middle mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th style="width:5%;">No</th>
                            <th style="width:8%;">Foto</th>
                            <th style="width:10%;">NIS</th>
                            <th>Nama</th>
                            <th style="width:12%;">Kelas</th>
                            <th style="width:15%;">Jurusan</th>
                            <th style="width:12%;">No HP</th>
                            <th class="text-center" style="width:12%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswas as $i => $siswa)
                        <tr>
                            <td>{{ $siswas->firstItem() + $i }}</td>
                            <td>
                                @if($siswa->foto)
                                    <img src="{{ asset('storage/'.$siswa->foto) }}"
                                         class="rounded-circle border shadow-sm" style="height:40px; width:40px; object-fit:cover;">
                                @else
                                    <div class="rounded-circle bg-light d-flex justify-content-center align-items-center border"
                                         style="height:40px; width:40px; font-size:12px; color:#6c757d;">
                                        <i class="bi bi-person"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="fw-semibold text-primary">{{ $siswa->nis }}</td>
                            <td class="fw-semibold text-dark">{{ $siswa->nama }}</td>
                            <td><i class="bi bi-easel me-1 text-secondary"></i> {{ $siswa->kelas->nama ?? '-' }}</td>
                            <td>{{ $siswa->jurusan ?? '-' }}</td>
                            <td><i class="bi bi-telephone me-1 text-success"></i> {{ $siswa->no_hp ?? '-' }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('siswa.edit', $siswa) }}" class="btn btn-sm btn-warning text-white shadow-sm"
                                       title="Edit" data-bs-toggle="tooltip">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('siswa.destroy', $siswa) }}" method="POST"
                                          onsubmit="return confirm('Yakin hapus siswa ini?')" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger shadow-sm" title="Hapus" data-bs-toggle="tooltip">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tidak ada data siswa.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $siswas->withQueryString()->links() }}
        </div>
    </div>
</div>

{{-- Include Modal Create Siswa --}}
@include('siswa.create-modal')

{{-- Style tambahan --}}
<style>
    .hover-shadow:hover {
        transform: translateY(-3px);
        transition: 0.2s;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
    }
</style>

{{-- Script untuk tooltip --}}
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endsection
