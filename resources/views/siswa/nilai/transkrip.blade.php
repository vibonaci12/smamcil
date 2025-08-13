@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-primary">
            <i class="bi bi-file-earmark-text me-2"></i> Transkrip Nilai
        </h1>
        <div class="d-flex gap-2">
            <button onclick="printTranskrip()" class="btn btn-primary">
                <i class="bi bi-printer me-1"></i> Cetak
            </button>
            <a href="{{ route('siswa.nilai') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person-badge me-2"></i> Transkrip Nilai Saya
                    </h5>
                </div>
        <div class="card-body">
            <!-- Student Information -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="text-primary mb-3">Informasi Siswa</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td width="150"><strong>Nama:</strong></td>
                            <td>{{ $transkrip['siswa']->nama }}</td>
                        </tr>
                        <tr>
                            <td><strong>NIS:</strong></td>
                            <td>{{ $transkrip['siswa']->nis }}</td>
                        </tr>
                        <tr>
                            <td><strong>Kelas:</strong></td>
                            <td>{{ $transkrip['siswa']->kelas->nama ?? 'Tidak ada kelas' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5 class="text-primary mb-3">Ringkasan Nilai</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td width="150"><strong>Rata-rata:</strong></td>
                            <td>
                                @if($transkrip['rata_rata'])
                                    <span class="badge bg-primary fs-6">{{ $transkrip['rata_rata'] }}</span>
                                    <span class="badge 
                                        @if($transkrip['rata_rata'] >= 85) bg-success
                                        @elseif($transkrip['rata_rata'] >= 75) bg-info
                                        @elseif($transkrip['rata_rata'] >= 60) bg-warning
                                        @else bg-danger
                                        @endif ms-2">
                                        {{ $transkrip['status_rata_rata'] }}
                                    </span>
                                @else
                                    <span class="text-muted">Belum ada nilai</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Mata Pelajaran:</strong></td>
                            <td>{{ $transkrip['jumlah_mata_pelajaran'] }} mata pelajaran</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Transcript Table -->
            <div class="mb-4">
                <h5 class="text-primary mb-3">Transkrip Nilai</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center" width="50">No</th>
                                <th>Mata Pelajaran</th>
                                <th>Guru</th>
                                <th class="text-center">Nilai Akhir</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transkrip['transkrip'] as $index => $nilai)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <i class="bi bi-book me-1 text-primary"></i>
                                        <strong>{{ $nilai['mata_pelajaran'] }}</strong>
                                    </td>
                                    <td>
                                        <i class="bi bi-person-badge me-1 text-success"></i>
                                        {{ $nilai['guru'] }}
                                    </td>
                                    <td class="text-center">
                                        @if($nilai['nilai_akhir'])
                                            <span class="badge bg-primary fs-6">{{ $nilai['nilai_akhir'] }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($nilai['nilai_akhir'])
                                            <span class="badge 
                                                @if($nilai['nilai_akhir'] >= 85) bg-success
                                                @elseif($nilai['nilai_akhir'] >= 75) bg-info
                                                @elseif($nilai['nilai_akhir'] >= 60) bg-warning
                                                @else bg-danger
                                                @endif">
                                                {{ $nilai['status'] }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if(!empty($nilai['nilai_detail']))
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    onclick="showDetail({{ json_encode($nilai['nilai_detail']) }}, '{{ $nilai['mata_pelajaran'] }}')">
                                                <i class="bi bi-eye me-1"></i>Detail
                                            </button>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="bi bi-star display-4"></i>
                                        <h6 class="mt-2">Belum ada data nilai</h6>
                                        <p class="mb-0">Nilai akan muncul setelah guru menginput nilai untuk Anda.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i> Informasi Siswa
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info border-0 bg-light">
                        <h6 class="fw-semibold">Data Siswa:</h6>
                        <ul class="mb-0">
                            <li><strong>Nama:</strong> {{ $transkrip['siswa']->nama }}</li>
                            <li><strong>NIS:</strong> {{ $transkrip['siswa']->nis }}</li>
                            <li><strong>Kelas:</strong> {{ $transkrip['siswa']->kelas->nama ?? 'Tidak ada kelas' }}</li>
                        </ul>
                    </div>

                    <div class="alert alert-success border-0 bg-light">
                        <h6 class="fw-semibold">Ringkasan Nilai:</h6>
                        <ul class="mb-0">
                            <li><strong>Rata-rata:</strong> 
                                @if($transkrip['rata_rata'])
                                    {{ $transkrip['rata_rata'] }} ({{ $transkrip['status_rata_rata'] }})
                                @else
                                    Belum ada nilai
                                @endif
                            </li>
                            <li><strong>Mata Pelajaran:</strong> {{ $transkrip['jumlah_mata_pelajaran'] }} mapel</li>
                        </ul>
                    </div>

                    <!-- Statistics Cards -->
                    @if($transkrip['jumlah_mata_pelajaran'] > 0)
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center p-2">
                                        <i class="bi bi-book h4"></i>
                                        <h6 class="mt-1 mb-0">{{ $transkrip['jumlah_mata_pelajaran'] }}</h6>
                                        <small>Mapel</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center p-2">
                                        <i class="bi bi-star h4"></i>
                                        <h6 class="mt-1 mb-0">{{ $transkrip['rata_rata'] ?? '-' }}</h6>
                                        <small>Rata-rata</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center p-2">
                                        <i class="bi bi-check-circle h4"></i>
                                        <h6 class="mt-1 mb-0">
                                            @php
                                                $lulusCount = collect($transkrip['transkrip'])->filter(function($nilai) {
                                                    return $nilai['nilai_akhir'] && $nilai['nilai_akhir'] >= 60;
                                                })->count();
                                            @endphp
                                            {{ $lulusCount }}
                                        </h6>
                                        <small>Lulus</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center p-2">
                                        <i class="bi bi-exclamation-triangle h4"></i>
                                        <h6 class="mt-1 mb-0">
                                            @php
                                                $tidakLulusCount = collect($transkrip['transkrip'])->filter(function($nilai) {
                                                    return $nilai['nilai_akhir'] && $nilai['nilai_akhir'] < 60;
                                                })->count();
                                            @endphp
                                            {{ $tidakLulusCount }}
                                        </h6>
                                        <small>Tidak Lulus</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Detail Nilai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- Content will be populated by JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
function showDetail(nilaiDetail, mataPelajaran) {
    const modal = document.getElementById('detailModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalContent = document.getElementById('modalContent');
    
    modalTitle.textContent = `Detail Nilai - ${mataPelajaran}`;
    
    let content = '<div class="table-responsive"><table class="table table-bordered">';
    content += '<thead class="table-light"><tr><th>Jenis Penilaian</th><th class="text-center">Nilai</th><th class="text-center">Bobot</th></tr></thead><tbody>';
    
    nilaiDetail.forEach(detail => {
        content += `
            <tr>
                <td><strong>${detail.jenis}</strong></td>
                <td class="text-center">
                    <span class="badge bg-primary">${detail.nilai}</span>
                </td>
                <td class="text-center">${detail.bobot}%</td>
            </tr>
        `;
    });
    
    content += '</tbody></table></div>';
    
    modalContent.innerHTML = content;
    new bootstrap.Modal(modal).show();
}

function printTranskrip() {
    window.print();
}
</script>

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
