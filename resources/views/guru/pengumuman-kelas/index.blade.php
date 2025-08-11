@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4 fw-bold text-primary"><i class="bi bi-megaphone me-2"></i> Manajemen Pengumuman Kelas</h1>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-semibold text-dark d-flex justify-content-between align-items-center">
            <span><i class="bi bi-megaphone me-2"></i> Daftar Pengumuman Kelas</span>
            <a href="{{ route('pengumuman-kelas.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Tambah Pengumuman
            </a>
        </div>
        <div class="card-body p-0">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    ✅ {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($pengumumanKelas->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Kelas</th>
                                <th>Tanggal</th>
                                <th>Isi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pengumumanKelas as $index => $pengumuman)
                                <tr>
                                    <td>{{ $pengumumanKelas->firstItem() + $index }}</td>
                                    <td>
                                        <i class="bi bi-megaphone me-1 text-primary"></i>
                                        <strong>{{ $pengumuman->judul }}</strong>
                                    </td>
                                    <td>
                                        <i class="bi bi-easel me-1 text-secondary"></i>
                                        <span class="badge bg-secondary">{{ $pengumuman->kelas->nama ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <i class="bi bi-calendar me-1 text-info"></i>
                                        {{ $pengumuman->tanggal->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        <i class="bi bi-chat-text me-1 text-success"></i>
                                        {{ Str::limit($pengumuman->isi, 80) }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('pengumuman-kelas.edit', $pengumuman->id) }}" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('pengumuman-kelas.destroy', $pengumuman->id) }}" method="POST" 
                                                  onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?')" class="d-inline">
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

                @if($pengumumanKelas->hasPages())
                    <div class="d-flex justify-content-center p-3">
                        {{ $pengumumanKelas->links() }}
                    </div>
                @endif
            @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-megaphone display-1"></i>
                    <h5 class="mt-3">Belum ada pengumuman kelas</h5>
                    <p>Mulai dengan menambahkan pengumuman untuk kelas Anda.</p>
                    <a href="{{ route('pengumuman-kelas.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Pengumuman Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 