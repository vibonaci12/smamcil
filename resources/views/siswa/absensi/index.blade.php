@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4 fw-bold text-primary"><i class="bi bi-clipboard-check me-2"></i> Absensi Saya</h1>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-semibold text-dark">
            <i class="bi bi-clipboard-check me-2"></i> Daftar Absensi
        </div>
        <div class="card-body p-0">
            @if($absensis->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Mata Pelajaran</th>
                                <th>Guru</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($absensis as $index => $absensi)
                                <tr>
                                    <td>{{ $absensis->firstItem() + $index }}</td>
                                    <td>
                                        <i class="bi bi-calendar me-1 text-info"></i>
                                        <strong>{{ $absensi->tanggal->format('d/m/Y') }}</strong>
                                    </td>
                                    <td>
                                        <i class="bi bi-book me-1 text-primary"></i>
                                        <strong>{{ $absensi->jadwal->mapel ?? '-' }}</strong>
                                    </td>
                                    <td>
                                        <i class="bi bi-person-badge me-1 text-success"></i>
                                        {{ $absensi->jadwal->guru->nama ?? '-' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $absensi->status == 'Hadir' ? 'success' : ($absensi->status == 'Sakit' ? 'warning' : 'danger') }} fs-6">
                                            {{ $absensi->status }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($absensi->keterangan)
                                            <i class="bi bi-chat-text me-1 text-secondary"></i>
                                            {{ $absensi->keterangan }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($absensis->hasPages())
                    <div class="d-flex justify-content-center p-3">
                        {{ $absensis->links() }}
                    </div>
                @endif
            @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-clipboard-x display-1"></i>
                    <h5 class="mt-3">Belum ada data absensi</h5>
                    <p>Data absensi akan muncul setelah guru menginput absensi untuk Anda.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 