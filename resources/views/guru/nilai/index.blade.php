@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4 fw-bold text-primary"><i class="bi bi-star me-2"></i> Manajemen Nilai</h1>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-semibold text-dark d-flex justify-content-between align-items-center">
            <span><i class="bi bi-star me-2"></i> Daftar Nilai Siswa</span>
            <a href="{{ route('nilai.select') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Input Nilai Batch
            </a>
        </div>
        <div class="card-body p-0">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    ✅ {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($nilais->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                                <th>Jenis Penilaian</th>
                                <th>Nilai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($nilais as $index => $nilai)
                                <tr>
                                    <td>{{ $nilais->firstItem() + $index }}</td>
                                    <td>
                                        <i class="bi bi-person-circle me-1 text-success"></i>
                                        <strong>{{ $nilai->siswa->nama ?? '-' }}</strong>
                                        <br><small class="text-muted">{{ $nilai->siswa->nis ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <i class="bi bi-easel me-1 text-secondary"></i>
                                        {{ $nilai->jadwal->kelas->nama ?? '-' }}
                                    </td>
                                    <td>
                                        <i class="bi bi-book me-1 text-primary"></i>
                                        {{ $nilai->jadwal->mapel ?? '-' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $nilai->jenisPenilaian->nama_formatted ?? '-' }}
                                            <small class="d-block">{{ $nilai->jenisPenilaian->bobot_formatted ?? '-' }}</small>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $nilai->status }}">
                                            {{ $nilai->nilai_formatted }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('nilai.editBatch', ['jadwal_id' => $nilai->jadwal_id, 'jenis_penilaian_id' => $nilai->jenis_penilaian_id]) }}" 
                                               class="btn btn-sm btn-warning" title="Edit Batch">
                                                <i class="bi bi-pencil"></i> Edit Batch
                                            </a>
                                        </div>
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
                    <h5 class="mt-3">Belum ada data nilai</h5>
                    <p>Mulai dengan menginput nilai siswa secara batch.</p>
                    <a href="{{ route('nilai.select') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Input Nilai Batch Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 