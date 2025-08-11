@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-primary">
            <i class="bi bi-star me-2"></i> Pilih Jadwal & Jenis Penilaian
        </h1>
        <a href="{{ route('nilai.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-check me-2"></i> Input Nilai Batch
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('nilai.createBatch') }}" method="GET">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jadwal_id" class="form-label fw-semibold">
                                    <i class="bi bi-calendar3 me-1 text-primary"></i> Pilih Jadwal
                                </label>
                                <select class="form-select" name="jadwal_id" id="jadwal_id" required>
                                    <option value="">Pilih Jadwal Mengajar</option>
                                    @foreach($jadwals as $jadwal)
                                        <option value="{{ $jadwal->id }}">
                                            {{ $jadwal->kelas->nama }} - {{ $jadwal->mapel }}
                                            <br><small class="text-muted">{{ $jadwal->hari }} ({{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }})</small>
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="jenis_penilaian_id" class="form-label fw-semibold">
                                    <i class="bi bi-list-check me-1 text-success"></i> Pilih Jenis Penilaian
                                </label>
                                <select class="form-select" name="jenis_penilaian_id" id="jenis_penilaian_id" required>
                                    <option value="">Pilih Jenis Penilaian</option>
                                    @foreach($jenisPenilaians as $jenis)
                                        <option value="{{ $jenis->id }}">
                                            {{ ucfirst($jenis->nama) }} (Bobot: {{ $jenis->bobot }}%)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-arrow-right me-2"></i> Lanjutkan ke Input Nilai
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i> Informasi
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info border-0 bg-light">
                        <h6 class="fw-semibold">Cara Input Nilai Batch:</h6>
                        <ol class="mb-0">
                            <li>Pilih jadwal mengajar yang sesuai</li>
                            <li>Pilih jenis penilaian (Tugas, UTS, UAS)</li>
                            <li>Sistem akan menampilkan semua siswa dalam kelas tersebut</li>
                            <li>Input nilai untuk setiap siswa dalam satu tabel</li>
                            <li>Gunakan <strong>updateOrCreate</strong> untuk efisiensi</li>
                        </ol>
                    </div>

                    <div class="alert alert-warning border-0 bg-light">
                        <h6 class="fw-semibold">Fitur Batch Input:</h6>
                        <ul class="mb-0">
                            <li>Input semua nilai siswa sekaligus</li>
                            <li>Auto-update nilai yang sudah ada</li>
                            <li>Validasi range nilai (0-100)</li>
                            <li>Hanya untuk kelas yang diajar</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-select option {
        padding: 8px;
        line-height: 1.4;
    }
    
    .form-select option small {
        color: #6c757d;
        font-size: 0.875em;
    }
</style>
@endsection
