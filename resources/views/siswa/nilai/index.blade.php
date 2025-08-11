@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4 fw-bold text-primary"><i class="bi bi-star me-2"></i> Nilai Saya</h1>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-semibold text-dark">
            <i class="bi bi-star me-2"></i> Daftar Nilai
        </div>
        <div class="card-body p-0">
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
                                        <span class="badge bg-primary">{{ ucfirst($nilai->jenis) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $nilai->nilai >= 75 ? 'success' : ($nilai->nilai >= 60 ? 'warning' : 'danger') }} fs-6">
                                            {{ $nilai->nilai }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($nilai->nilai >= 75)
                                            <span class="badge bg-success">Lulus</span>
                                        @elseif($nilai->nilai >= 60)
                                            <span class="badge bg-warning">Cukup</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Lulus</span>
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
@endsection 