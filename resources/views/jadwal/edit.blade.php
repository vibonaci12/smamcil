@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Judul Halaman -->
    <div class="p-4 rounded shadow-sm mb-4 bg-warning text-white d-flex justify-content-between align-items-center">
        <h1 class="fw-bold mb-0">
            <i class="bi bi-pencil-square me-2"></i> Edit Jadwal
        </h1>
        <a href="{{ route('jadwal.index') }}" class="btn btn-light shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <!-- Notifikasi Error -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Form Edit Jadwal -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light border-bottom">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-calendar3 me-2 text-warning"></i> Edit Data Jadwal
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('jadwal.update', $jadwal) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="kelas_id" class="form-label fw-semibold">
                            <i class="bi bi-easel me-1 text-primary"></i> Kelas
                        </label>
                        <select class="form-select @error('kelas_id') is-invalid @enderror" name="kelas_id" required>
                            <option value="">Pilih Kelas</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" @selected(old('kelas_id', $jadwal->kelas_id) == $kelas->id)>
                                    {{ $kelas->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('kelas_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="mapel" class="form-label fw-semibold">
                            <i class="bi bi-book me-1 text-info"></i> Mata Pelajaran
                        </label>
                        <input type="text" class="form-control @error('mapel') is-invalid @enderror" 
                            name="mapel" value="{{ old('mapel', $jadwal->mapel) }}" required>
                        @error('mapel')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="guru_id" class="form-label fw-semibold">
                            <i class="bi bi-person-badge me-1 text-success"></i> Guru
                        </label>
                        <select class="form-select @error('guru_id') is-invalid @enderror" name="guru_id" required>
                            <option value="">Pilih Guru</option>
                            @foreach($guruList as $guru)
                                <option value="{{ $guru->id }}" @selected(old('guru_id', $jadwal->guru_id) == $guru->id)>
                                    {{ $guru->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('guru_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="hari" class="form-label fw-semibold">
                            <i class="bi bi-calendar-week me-1 text-secondary"></i> Hari
                        </label>
                        <select class="form-select @error('hari') is-invalid @enderror" name="hari" required>
                            <option value="">Pilih Hari</option>
                            @foreach($hariList as $hari)
                                <option value="{{ $hari }}" @selected(old('hari', $jadwal->hari) == $hari)>
                                    {{ $hari }}
                                </option>
                            @endforeach
                        </select>
                        @error('hari')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="jam_mulai" class="form-label fw-semibold">
                            <i class="bi bi-clock me-1 text-warning"></i> Jam Mulai (24 Jam)
                        </label>
                        <input type="time" class="form-control @error('jam_mulai') is-invalid @enderror" 
                            name="jam_mulai" value="{{ old('jam_mulai', $jadwal->jam_mulai) }}" 
                            step="900" min="00:00" max="23:59" required>
                        <small class="form-text text-muted">Format 24 jam (contoh: 07:00, 13:30, 15:45)</small>
                        @error('jam_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="jam_selesai" class="form-label fw-semibold">
                            <i class="bi bi-clock-fill me-1 text-danger"></i> Jam Selesai (24 Jam)
                        </label>
                        <input type="time" class="form-control @error('jam_selesai') is-invalid @enderror" 
                            name="jam_selesai" value="{{ old('jam_selesai', $jadwal->jam_selesai) }}" 
                            step="900" min="00:00" max="23:59" required>
                        <small class="form-text text-muted">Format 24 jam (contoh: 08:30, 14:30, 16:45)</small>
                        @error('jam_selesai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Info Tambahan -->
                <div class="alert alert-info border-0 bg-light mb-4">
                    <i class="bi bi-info-circle me-2 text-info"></i>
                    <strong>Tips:</strong> 
                    <ul class="mb-0 mt-2">
                        <li>Gunakan format 24 jam (00:00 - 23:59)</li>
                        <li>Jam mulai harus sebelum jam selesai</li>
                        <li>Pastikan jadwal tidak bentrok dengan jadwal kelas yang sama pada hari yang sama</li>
                    </ul>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('jadwal.index') }}" class="btn btn-secondary shadow-sm">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-warning text-white shadow-sm">
                        <i class="bi bi-save me-1"></i> Update Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
</style>

{{-- Script untuk time input validation --}}
<script>
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
        });
    });
</script>
@endsection
