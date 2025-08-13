@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0 fw-bold text-primary">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard Siswa
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('siswa.nilai.transkrip') }}" class="btn btn-primary">
                <i class="bi bi-file-earmark-text me-2"></i>Transkrip Nilai
            </a>
            <a href="{{ route('siswa.nilai.akhir') }}" class="btn btn-success">
                <i class="bi bi-table me-2"></i>Nilai Akhir
            </a>
        </div>
    </div>

    <!-- Jadwal Mata Pelajaran Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white fw-semibold text-dark d-flex justify-content-between align-items-center">
            <span><i class="bi bi-calendar-event me-2"></i> Jadwal Mata Pelajaran</span>
            <a href="{{ route('siswa.jadwal') }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-eye me-1"></i>Lihat Semua
            </a>
        </div>
        <div class="card-body p-0">
            @if($jadwalMingguan && count($jadwalMingguan))
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 100px;">Hari</th>
                                <th class="text-center" style="width: 120px;">Jam</th>
                                <th>Mata Pelajaran</th>
                                <th>Guru</th>
                                <th class="text-center" style="width: 100px;">Kelas</th>
                                <th class="text-center" style="width: 120px;">Materi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jadwalMingguan as $jadwal)
                            <tr>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ $jadwal->hari }}</span>
                                </td>
                                <td class="text-center">
                                    <small class="text-muted">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-book me-2 text-primary"></i>
                                        <strong>{{ $jadwal->mapel }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-badge me-2 text-secondary"></i>
                                        {{ $jadwal->guru->nama ?? '-' }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $jadwal->kelas->nama ?? '-' }}</span>
                                </td>
                                <td class="text-center">
                                    @if($jadwal->materis && count($jadwal->materis))
                                        @foreach($jadwal->materis as $materi)
                                            <a href="{{ asset('storage/'.$materi->file) }}" 
                                               class="btn btn-sm btn-outline-info mb-1" 
                                               target="_blank"
                                               title="{{ $materi->judul }}">
                                                <i class="bi bi-file-earmark-text me-1"></i>
                                                {{ Str::limit($materi->judul, 15) }}
                                            </a>
                                        @endforeach
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-calendar-x display-1"></i>
                    <h5 class="mt-3">Tidak ada jadwal tersedia</h5>
                    <p>Jadwal mata pelajaran akan muncul di sini.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Pengumuman Terbaru Card -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-semibold text-dark d-flex justify-content-between align-items-center">
            <span><i class="bi bi-megaphone me-2"></i> Pengumuman Terbaru</span>
            <a href="{{ route('siswa.pengumuman') }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-eye me-1"></i>Lihat Semua
            </a>
        </div>
        <div class="card-body">
            @if($pengumumanTerbaru && count($pengumumanTerbaru))
                <div class="row g-3">
                    @foreach($pengumumanTerbaru as $pengumuman)
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title mb-0 fw-semibold">{{ $pengumuman->judul }}</h6>
                                    <small class="text-muted">{{ $pengumuman->tanggal }}</small>
                                </div>
                                <p class="card-text text-muted small">
                                    {{ Str::limit($pengumuman->isi, 100) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-primary">
                                        <i class="bi bi-clock me-1"></i>{{ $pengumuman->tanggal }}
                                    </small>
                                    <button class="btn btn-sm btn-outline-primary" 
                                            onclick="showPengumumanDetail('{{ $pengumuman->judul }}', '{{ $pengumuman->isi }}', '{{ $pengumuman->tanggal }}')">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-megaphone display-1"></i>
                    <h5 class="mt-3">Belum ada pengumuman terbaru</h5>
                    <p>Pengumuman dari guru akan muncul di sini.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Detail Pengumuman -->
<div class="modal fade" id="pengumumanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pengumumanModalTitle">Detail Pengumuman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <small class="text-muted" id="pengumumanModalDate"></small>
                </div>
                <div id="pengumumanModalContent">
                    <!-- Content will be populated by JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
function showPengumumanDetail(judul, isi, tanggal) {
    document.getElementById('pengumumanModalTitle').textContent = judul;
    document.getElementById('pengumumanModalDate').textContent = tanggal;
    document.getElementById('pengumumanModalContent').textContent = isi;
    new bootstrap.Modal(document.getElementById('pengumumanModal')).show();
}
</script>
@endsection 