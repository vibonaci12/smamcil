@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4 fw-bold text-primary"><i class="bi bi-clipboard-check me-2"></i> Absensi Terintegrasi</h1>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-semibold text-dark d-flex justify-content-between align-items-center">
            <span><i class="bi bi-clipboard-check me-2"></i> Daftar Absensi Terintegrasi</span>
            <a href="{{ route('absensi.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Tambah Absensi
            </a>
        </div>
        <div class="card-body p-0">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    ✅ {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($absensis->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($absensis as $index => $absensi)
                                <tr>
                                    <td>{{ $absensis->firstItem() + $index }}</td>
                                    <td>
                                        <i class="bi bi-person-circle me-1 text-success"></i>
                                        <strong>{{ $absensi->siswa->nama ?? '-' }}</strong>
                                        <br><small class="text-muted">{{ $absensi->siswa->nis ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <i class="bi bi-easel me-1 text-secondary"></i>
                                        {{ $absensi->jadwal->kelas->nama ?? '-' }}
                                    </td>
                                    <td>
                                        <i class="bi bi-book me-1 text-primary"></i>
                                        {{ $absensi->jadwal->mapel ?? '-' }}
                                    </td>
                                    <td>
                                        <i class="bi bi-calendar me-1 text-info"></i>
                                        {{ $absensi->tanggal->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $absensi->status == 'Hadir' ? 'success' : ($absensi->status == 'Sakit' ? 'warning' : 'danger') }}">
                                            {{ $absensi->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('absensi.edit', $absensi->id) }}" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('absensi.destroy', $absensi->id) }}" method="POST" 
                                                  onsubmit="return confirm('Yakin ingin menghapus absensi ini?')" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
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
                    <p>Mulai dengan menambahkan absensi terintegrasi (guru + siswa).</p>
                    <a href="{{ route('absensi.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Absensi Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 