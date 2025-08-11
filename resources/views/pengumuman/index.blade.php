@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold text-primary">
                        <i class="bi bi-megaphone-fill me-2"></i> Manajemen Pengumuman
                    </h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreatePengumuman">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Pengumuman
                    </button>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            ✅ {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th style="width:5%">No</th>
                                    <th>Judul</th>
                                    <th style="width:15%">Tanggal</th>
                                    <th>Isi</th>
                                    <th style="width:15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pengumumen as $index => $pengumuman)
                                    <tr>
                                        <td class="text-center">{{ $pengumumen->firstItem() + $index }}</td>
                                        <td>{{ $pengumuman->judul }}</td>
                                        <td class="text-center">{{ $pengumuman->tanggal->format('d/m/Y') }}</td>
                                        <td>{{ Str::limit($pengumuman->isi, 80) }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <!-- Detail Modal Trigger -->
                                                <button class="btn btn-sm btn-info text-white" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalDetailPengumuman{{ $pengumuman->id }}">
                                                    <i class="bi bi-eye"></i>
                                                </button>

                                                <!-- Edit Modal Trigger -->
                                                <button class="btn btn-sm btn-warning" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalEditPengumuman{{ $pengumuman->id }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>

                                                <!-- Hapus Form -->
                                                <form action="{{ route('pengumuman.destroy', $pengumuman->id) }}" method="POST" 
                                                      onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Include Modals Per Row -->
                                    @include('pengumuman.modal-detail', ['pengumuman' => $pengumuman])
                                    @include('pengumuman.modal-edit', ['pengumuman' => $pengumuman])
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">
                                            <i class="bi bi-exclamation-circle me-1"></i> Tidak ada pengumuman.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($pengumumen->hasPages())
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $pengumumen->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Create -->
@include('pengumuman.modal-create')

@endsection
