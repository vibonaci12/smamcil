@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4 fw-bold text-primary"><i class="bi bi-file-earmark-text me-2"></i> Manajemen Materi</h1>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-semibold text-dark d-flex justify-content-between align-items-center">
            <span><i class="bi bi-file-earmark-text me-2"></i> Daftar Materi Pembelajaran</span>
            <a href="{{ route('materi.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Upload Materi
            </a>
        </div>
        <div class="card-body p-0">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    ✅ {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($materis->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                                <th>File</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($materis as $index => $materi)
                                <tr>
                                    <td>{{ $materis->firstItem() + $index }}</td>
                                    <td>
                                        <i class="bi bi-file-earmark-text me-1 text-primary"></i>
                                        <strong>{{ $materi->judul }}</strong>
                                        @if($materi->deskripsi)
                                            <br><small class="text-muted">{{ Str::limit($materi->deskripsi, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <i class="bi bi-easel me-1 text-secondary"></i>
                                        {{ $materi->kelas->nama ?? '-' }}
                                    </td>
                                    <td>
                                        <i class="bi bi-book me-1 text-info"></i>
                                        {{ $materi->jadwal->mapel ?? '-' }}
                                    </td>
                                    <td>
                                        @if($materi->file)
                                            <a href="{{ Storage::url($materi->file) }}" 
                                               class="btn btn-sm btn-info" target="_blank" title="Download">
                                                <i class="bi bi-download"></i> Download
                                            </a>
                                        @else
                                            <span class="text-muted">Tidak ada file</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('materi.edit', $materi->id) }}" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('materi.destroy', $materi->id) }}" method="POST" 
                                                  onsubmit="return confirm('Yakin ingin menghapus materi ini?')" class="d-inline">
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

                @if($materis->hasPages())
                    <div class="d-flex justify-content-center p-3">
                        {{ $materis->links() }}
                    </div>
                @endif
            @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-file-earmark-x display-1"></i>
                    <h5 class="mt-3">Belum ada materi</h5>
                    <p>Mulai dengan mengupload materi pembelajaran.</p>
                    <a href="{{ route('materi.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Upload Materi Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 