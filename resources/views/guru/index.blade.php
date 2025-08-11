@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Judul Halaman -->
    <div class="p-4 rounded shadow-sm mb-4 bg-primary text-white d-flex justify-content-between align-items-center">
        <h1 class="fw-bold mb-0">
            <i class="bi bi-person-badge me-2"></i> Manajemen Guru
        </h1>
        <button type="button" class="btn btn-light shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCreateGuru">
            <i class="bi bi-plus-lg me-1"></i> Tambah Guru
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
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama guru..." class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="mapel" class="form-select">
                        <option value="">Semua Mapel</option>
                        @foreach($mapelList as $mapel)
                            <option value="{{ $mapel }}" @selected(request('mapel') == $mapel)>{{ $mapel }}</option>
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

    <!-- Tabel Guru -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-table me-2 text-primary"></i> Data Guru
            </h5>
            <span class="badge bg-primary">{{ $gurus->total() }} Guru</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered align-middle mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th style="width:5%">No</th>
                            <th style="width:8%">Foto</th>
                            <th style="width:10%">NIP</th>
                            <th>Nama</th>
                            <th style="width:12%">Mapel</th>
                            <th style="width:10%">Jenis Kelamin</th>
                            <th style="width:12%">No HP</th>
                            <th style="width:8%">Status Akun</th>
                            <th style="width:12%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gurus as $i => $guru)
                        <tr>
                            <td>{{ $gurus->firstItem() + $i }}</td>
                            <td>
                                @if($guru->foto)
                                    <img src="{{ asset('storage/'.$guru->foto) }}" alt="foto" class="rounded-circle border shadow-sm" style="height:40px;width:40px;object-fit:cover;">
                                @else
                                    <div class="rounded-circle bg-light d-flex justify-content-center align-items-center border"
                                         style="height:40px; width:40px; font-size:12px; color:#6c757d;">
                                        <i class="bi bi-person"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="fw-semibold text-primary">{{ $guru->nip }}</td>
                            <td class="fw-semibold text-dark">{{ $guru->nama }}</td>
                            <td><i class="bi bi-book me-1 text-info"></i> {{ $guru->mapel }}</td>
                            <td>
                                @if($guru->jenis_kelamin == 'L')
                                    <span class="badge bg-primary"><i class="bi bi-gender-male me-1"></i> Laki-laki</span>
                                @else
                                    <span class="badge bg-pink"><i class="bi bi-gender-female me-1"></i> Perempuan</span>
                                @endif
                            </td>
                            <td><i class="bi bi-telephone me-1 text-success"></i> {{ $guru->no_hp ?? '-' }}</td>
                            <td>
                                @if($guru->user)
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Aktif</span>
                                    <small class="d-block text-muted">Username: {{ $guru->user->username }}</small>
                                @else
                                    <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle me-1"></i> No Account</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('guru.edit', $guru) }}" class="btn btn-warning btn-sm text-white shadow-sm" title="Edit" data-bs-toggle="tooltip">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('guru.destroy', $guru) }}" method="POST" onsubmit="return confirm('Yakin hapus guru ini? Akun login juga akan dihapus.')" class="d-inline">
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
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tidak ada data guru.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $gurus->withQueryString()->links() }}
        </div>
    </div>
</div>

{{-- Include Modal Create Guru --}}
@include('guru.create-modal')

{{-- Style tambahan --}}
<style>
    .hover-shadow:hover {
        transform: translateY(-3px);
        transition: 0.2s;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
    }
    .badge.bg-pink {
        background-color: #e91e63 !important;
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