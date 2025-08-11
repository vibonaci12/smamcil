@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Judul Halaman -->
    <div class="p-4 rounded shadow-sm mb-4 bg-primary text-white d-flex justify-content-between align-items-center">
        <h1 class="fw-bold mb-0">
            <i class="bi bi-easel me-2"></i> Manajemen Kelas
        </h1>
        <button type="button" class="btn btn-light shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCreateKelas">
            <i class="bi bi-plus-lg me-1"></i> Tambah Kelas
        </button>
    </div>

    <!-- Notifikasi Sukses -->
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
                        <input type="text" name="q" value="{{ request('q') }}" 
                            placeholder="Cari nama kelas..." class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="wali_kelas_id" class="form-select">
                        <option value="">Semua Wali Kelas</option>
                        @foreach($guruList as $guru)
                            <option value="{{ $guru->id }}" @selected(request('wali_kelas_id') == $guru->id)>
                                {{ $guru->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Kelas -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-table me-2 text-primary"></i> Data Kelas
            </h5>
            <span class="badge bg-primary">{{ $kelas->total() }} Kelas</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered align-middle mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th style="width:5%">No</th>
                            <th style="width:25%">Nama Kelas</th>
                            <th style="width:20%">Jurusan</th>
                            <th style="width:25%">Wali Kelas</th>
                            <th style="width:15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelas as $i => $kls)
                        <tr>
                            <td class="text-center">{{ $kelas->firstItem() + $i }}</td>
                            <td>
                                <i class="bi bi-easel text-primary me-1"></i>
                                <span class="fw-semibold text-dark">{{ $kls->nama }}</span>
                            </td>
                            <td>{{ $kls->jurusan ?? '-' }}</td>
                            <td>
                                <i class="bi bi-person-badge text-success me-1"></i>
                                {{ $kls->waliKelas->nama ?? '-' }}
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('kelas.edit', $kls) }}" 
                                        class="btn btn-warning btn-sm text-white shadow-sm" title="Edit" data-bs-toggle="tooltip">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('kelas.destroy', $kls) }}" method="POST" 
                                        onsubmit="return confirm('Yakin hapus kelas ini?')" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm shadow-sm" title="Hapus" data-bs-toggle="tooltip">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tidak ada data kelas.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $kelas->withQueryString()->links() }}
        </div>
    </div>
</div>

{{-- Include Modal Create Kelas --}}
@include('kelas.modal-create')

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
