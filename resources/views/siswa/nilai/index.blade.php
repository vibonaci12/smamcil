@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-primary">
            <i class="bi bi-star me-2"></i> Nilai Saya
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('siswa.nilai.transkrip') }}" class="btn btn-success">
                <i class="bi bi-file-earmark-text me-1"></i> Transkrip
            </a>
            <a href="{{ route('siswa.nilai.akhir') }}" class="btn btn-info">
                <i class="bi bi-table me-1"></i> Nilai Akhir
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-star me-2"></i> Daftar Nilai
                    </h5>
                </div>
        <div class="card-body p-0">
            <!-- Summary Cards -->
            @if($nilais->count() > 0)
                <div class="row p-3">
                    <div class="col-md-3 mb-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-star display-6"></i>
                                <h4 class="mt-2">{{ $nilais->total() }}</h4>
                                <p class="mb-0">Total Nilai</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-check-circle display-6"></i>
                                <h4 class="mt-2">
                                    @php
                                        $sangatBaikCount = $nilais->filter(function($nilai) {
                                            return $nilai->nilai >= 85;
                                        })->count();
                                    @endphp
                                    {{ $sangatBaikCount }}
                                </h4>
                                <p class="mb-0">Sangat Baik</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-star-fill display-6"></i>
                                <h4 class="mt-2">
                                    @php
                                        $baikCount = $nilais->filter(function($nilai) {
                                            return $nilai->nilai >= 75 && $nilai->nilai < 85;
                                        })->count();
                                    @endphp
                                    {{ $baikCount }}
                                </h4>
                                <p class="mb-0">Baik</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-exclamation-triangle display-6"></i>
                                <h4 class="mt-2">
                                    @php
                                        $cukupCount = $nilais->filter(function($nilai) {
                                            return $nilai->nilai >= 60 && $nilai->nilai < 75;
                                        })->count();
                                    @endphp
                                    {{ $cukupCount }}
                                </h4>
                                <p class="mb-0">Cukup</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if($nilais->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Mata Pelajaran</th>
                                <th>Guru</th>
                                <th>Jenis</th>
                                <th>Nilai</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($nilais as $index => $nilai)
                                <tr>
                                    <td>{{ $nilais->firstItem() + $index }}</td>
                                    <td>
                                        <i class="bi bi-book me-1 text-primary"></i>
                                        <strong>{{ $nilai->jadwal->mapel ?? '-' }}</strong>
                                    </td>
                                    <td>
                                        <i class="bi bi-person-badge me-1 text-success"></i>
                                        {{ $nilai->jadwal->guru->nama ?? '-' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $nilai->jenisPenilaian->nama ?? ucfirst($nilai->jenis) }}
                                            @if($nilai->jenisPenilaian)
                                                <small class="d-block">{{ $nilai->jenisPenilaian->bobot }}%</small>
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $nilai->nilai >= 75 ? 'success' : ($nilai->nilai >= 60 ? 'warning' : 'danger') }} fs-6">
                                            {{ number_format($nilai->nilai, 1) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($nilai->nilai >= 85)
                                            <span class="badge bg-success">Sangat Baik</span>
                                        @elseif($nilai->nilai >= 75)
                                            <span class="badge bg-info">Baik</span>
                                        @elseif($nilai->nilai >= 60)
                                            <span class="badge bg-warning">Cukup</span>
                                        @else
                                            <span class="badge bg-danger">Kurang</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($nilais->hasPages())
                    <div class="d-flex justify-content-center p-3">
                        {{ $nilais->links() }}
                    </div>
                @endif
            @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-star display-1"></i>
                    <h5 class="mt-3">Belum ada nilai</h5>
                    <p>Nilai akan muncul setelah guru menginput nilai untuk Anda.</p>
                </div>
            @endif
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
                        <h6 class="fw-semibold">Fitur Nilai Siswa:</h6>
                        <ul class="mb-0">
                            <li><strong>Daftar Nilai:</strong> Lihat semua nilai per mata pelajaran</li>
                            <li><strong>Transkrip:</strong> Lihat transkrip lengkap dengan rata-rata</li>
                            <li><strong>Nilai Akhir:</strong> Lihat nilai akhir per mata pelajaran</li>
                            <li><strong>Detail:</strong> Lihat detail perhitungan bobot nilai</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning border-0 bg-light">
                        <h6 class="fw-semibold">Kriteria Nilai:</h6>
                        <ul class="mb-0">
                            <li><strong>Sangat Baik (A):</strong> ≥ 85</li>
                            <li><strong>Baik (B):</strong> ≥ 75</li>
                            <li><strong>Cukup (C):</strong> ≥ 60</li>
                            <li><strong>Kurang (D):</strong> < 60</li>
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