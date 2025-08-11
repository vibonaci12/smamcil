@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Judul Halaman -->
    <div class="p-4 rounded shadow-sm mb-4 bg-primary text-white d-flex justify-content-between align-items-center">
        <h1 class="fw-bold mb-0">
            <i class="bi bi-calendar3 me-2"></i> Manajemen Jadwal
        </h1>
        <button type="button" class="btn btn-light shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCreateJadwal">
            <i class="bi bi-plus-lg me-1"></i> Tambah Jadwal
        </button>
    </div>

    <!-- Notifikasi Sukses -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Notifikasi Error -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" value="{{ request('q') }}" 
                            placeholder="Cari mata pelajaran..." class="form-control">
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
                @if(Auth::user()->role === 'admin')
                <div class="col-md-3">
                    <select name="guru_id" class="form-select">
                        <option value="">Semua Guru</option>
                        @foreach($guruList as $guru)
                            <option value="{{ $guru->id }}" @selected(request('guru_id') == $guru->id)>
                                {{ $guru->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-md-2">
                    <select name="hari" class="form-select">
                        <option value="">Semua Hari</option>
                        @foreach($hariList as $hari)
                            <option value="{{ $hari }}" @selected(request('hari') == $hari)>
                                {{ $hari }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Jadwal -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-table me-2 text-primary"></i> Data Jadwal
            </h5>
            <span class="badge bg-primary">{{ $jadwals->total() }} Jadwal</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered align-middle mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th style="width:5%">No</th>
                            <th style="width:20%">Kelas</th>
                            <th style="width:20%">Mata Pelajaran</th>
                            <th style="width:20%">Guru</th>
                            <th style="width:15%">Hari</th>
                            <th style="width:15%">Jam</th>
                            <th style="width:10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwals as $i => $jadwal)
                        <tr>
                            <td class="text-center">{{ $jadwals->firstItem() + $i }}</td>
                            <td>
                                <i class="bi bi-easel text-primary me-1"></i>
                                <span class="fw-semibold text-dark">{{ $jadwal->kelas->nama ?? '-' }}</span>
                            </td>
                            <td>
                                <i class="bi bi-book text-info me-1"></i>
                                <span class="fw-semibold text-dark">{{ $jadwal->mapel }}</span>
                            </td>
                            <td>
                                <i class="bi bi-person-badge text-success me-1"></i>
                                {{ $jadwal->guru->nama ?? '-' }}
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $jadwal->hari }}</span>
                            </td>
                            <td>
                                <i class="bi bi-clock text-warning me-1"></i>
                                <span class="time-display">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('jadwal.edit', $jadwal) }}" 
                                        class="btn btn-warning btn-sm text-white shadow-sm" title="Edit" data-bs-toggle="tooltip">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('jadwal.destroy', $jadwal) }}" method="POST" 
                                        onsubmit="return confirm('Yakin hapus jadwal ini?')" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm shadow-sm" title="Hapus" data-bs-toggle="tooltip">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                    @else
                                    <span class="badge bg-info">Jadwal Mengajar</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                                Tidak ada data jadwal.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $jadwals->withQueryString()->links() }}
        </div>
    </div>
</div>

{{-- Include Modal Create Jadwal --}}
@include('jadwal.create-modal')

{{-- Style tambahan --}}
<style>
    .hover-shadow:hover {
        transform: translateY(-3px);
        transition: 0.2s;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
    }
    
    /* Ensure time inputs display in 24-hour format */
    input[type="time"] {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        text-align: center;
    }
    
    /* Style for time display in table */
    .time-display {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        background: #f8f9fa;
        padding: 4px 8px;
        border-radius: 4px;
        border: 1px solid #dee2e6;
    }
    
    /* Improve table appearance */
    .table th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        font-weight: 600;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    /* Badge styling for days */
    .badge.bg-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%) !important;
        font-weight: 500;
        padding: 6px 12px;
    }
</style>

{{-- Script untuk tooltip dan auto-open modal --}}
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // Auto-open modal if there are validation errors
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            var modal = new bootstrap.Modal(document.getElementById('modalCreateJadwal'));
            modal.show();
        });
    @endif

    // Time input validation and formatting
    document.addEventListener('DOMContentLoaded', function() {
        const timeInputs = document.querySelectorAll('input[type="time"]');
        
        timeInputs.forEach(function(input) {
            // Set step to 15 minutes (900 seconds)
            input.step = 900;
            
            // Add event listener for better validation
            input.addEventListener('change', function() {
                const time = this.value;
                if (time) {
                    // Ensure time is in 24-hour format
                    const [hours, minutes] = time.split(':');
                    if (hours < 0 || hours > 23 || minutes < 0 || minutes > 59) {
                        this.setCustomValidity('Waktu harus dalam format 24 jam yang valid');
                    } else {
                        this.setCustomValidity('');
                    }
                }
            });
            
            // Add placeholder text
            if (input.name === 'jam_mulai') {
                input.placeholder = '07:00';
            } else if (input.name === 'jam_selesai') {
                input.placeholder = '08:30';
            }
        });
    });
</script>
@endsection 