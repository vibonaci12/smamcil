@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0 fw-bold text-primary">
            <i class="bi bi-file-earmark-text me-2"></i> Transkrip Siswa
        </h1>
        <a href="{{ route('nilai.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
                    <!-- Student Information -->
                    <div class="mb-4">
                        <h3 class="h5 fw-semibold mb-3">Informasi Siswa</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Nama:</strong> {{ $transkrip['siswa']->nama }}</p>
                                <p><strong>NIS:</strong> {{ $transkrip['siswa']->nis }}</p>
                                <p><strong>Kelas:</strong> {{ $transkrip['siswa']->kelas->nama ?? 'Tidak ada kelas' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Rata-rata Nilai:</strong> 
                                    @if($transkrip['rata_rata'])
                                        <span class="fw-semibold">{{ $transkrip['rata_rata'] }}</span>
                                        <span class="badge ms-2
                                            @if($transkrip['rata_rata'] >= 85) bg-success
                                            @elseif($transkrip['rata_rata'] >= 75) bg-primary
                                            @elseif($transkrip['rata_rata'] >= 60) bg-warning text-dark
                                            @else bg-danger
                                            @endif">
                                            {{ $transkrip['status_rata_rata'] }}
                                        </span>
                                    @else
                                        <span class="text-muted">Belum ada nilai</span>
                                    @endif
                                </p>
                                <p><strong>Jumlah Mata Pelajaran:</strong> {{ $transkrip['jumlah_mata_pelajaran'] }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Transcript Table -->
                    <div class="mb-4">
                        <h3 class="h5 fw-semibold mb-3">Transkrip Nilai</h3>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Mata Pelajaran</th>
                                        <th>Guru</th>
                                        <th class="text-center">Nilai Akhir</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transkrip['transkrip'] as $nilai)
                                        <tr>
                                            <td>{{ $nilai['mata_pelajaran'] }}</td>
                                            <td>{{ $nilai['guru'] }}</td>
                                            <td class="text-center">
                                                @if($nilai['nilai_akhir'])
                                                    <span class="fw-semibold">{{ $nilai['nilai_akhir'] }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($nilai['nilai_akhir'])
                                                    <span class="badge 
                                                        @if($nilai['nilai_akhir'] >= 85) bg-success
                                                        @elseif($nilai['nilai_akhir'] >= 75) bg-primary
                                                        @elseif($nilai['nilai_akhir'] >= 60) bg-warning text-dark
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
                                                    <button onclick="showDetail({{ json_encode($nilai['nilai_detail']) }}, '{{ $nilai['mata_pelajaran'] }}')" 
                                                            class="btn btn-sm btn-outline-primary">
                                                        Lihat Detail
                                                    </button>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                Belum ada data nilai
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <button onclick="printTranskrip()" class="btn btn-primary">
                            <i class="bi bi-printer me-2"></i>Cetak Transkrip
                        </button>
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
            const modalTitle = document.getElementById('modalTitle');
            const modalContent = document.getElementById('modalContent');
            
            modalTitle.textContent = `Detail Nilai - ${mataPelajaran}`;
            
            let content = '';
            nilaiDetail.forEach(detail => {
                content += `
                    <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded mb-2">
                        <span class="fw-medium">${detail.jenis}</span>
                        <div class="text-end">
                            <div class="fw-semibold">${detail.nilai}</div>
                            <div class="text-muted small">Bobot: ${detail.bobot}%</div>
                        </div>
                    </div>
                `;
            });
            
            modalContent.innerHTML = content;
            new bootstrap.Modal(document.getElementById('detailModal')).show();
        }
        
        function printTranskrip() {
            window.print();
        }
    </script>
@endsection
