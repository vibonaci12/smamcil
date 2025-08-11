@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4 fw-bold text-primary"><i class="bi bi-file-earmark-text me-2"></i> Materi Pembelajaran</h1>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-semibold text-dark">
            <i class="bi bi-file-earmark-text me-2"></i> Daftar Materi
        </div>
        <div class="card-body p-0">
            @if($materis->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Mata Pelajaran</th>
                                <th>Guru</th>
                                <th>Deskripsi</th>
                                <th>File</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($materis as $index => $materi)
                                <tr>
                                    <td>{{ $materis->firstItem() + $index }}</td>
                                    <td>
                                        <i class="bi bi-file-earmark-text me-1 text-primary"></i>
                                        <strong>{{ $materi->judul }}</strong>
                                    </td>
                                    <td>
                                        <i class="bi bi-book me-1 text-info"></i>
                                        {{ $materi->jadwal->mapel ?? '-' }}
                                    </td>
                                    <td>
                                        <i class="bi bi-person-badge me-1 text-success"></i>
                                        {{ $materi->guru->nama ?? '-' }}
                                    </td>
                                    <td>
                                        @if($materi->deskripsi)
                                            <i class="bi bi-chat-text me-1 text-secondary"></i>
                                            {{ Str::limit($materi->deskripsi, 50) }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($materi->file)
                                            <a href="{{ Storage::url($materi->file) }}" 
                                               class="btn btn-sm btn-success" target="_blank" title="Download">
                                                <i class="bi bi-download"></i> Download
                                            </a>
                                        @else
                                            <span class="text-muted">Tidak ada file</span>
                                        @endif
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
                    <p>Materi pembelajaran akan muncul setelah guru mengupload materi untuk kelas Anda.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 