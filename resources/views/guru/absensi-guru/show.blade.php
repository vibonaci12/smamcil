@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0 fw-bold text-primary">
            <i class="bi bi-eye me-2"></i> Detail Absensi Guru
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('absensi-guru.edit', $absensiGuru->id) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
            <a href="{{ route('absensi-guru.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light fw-semibold text-dark">
                    <i class="bi bi-calendar-check me-2"></i> Informasi Absensi
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Kelas:</label>
                                <p class="mb-0">{{ $absensiGuru->jadwal->kelas->nama }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Mata Pelajaran:</label>
                                <p class="mb-0">{{ $absensiGuru->jadwal->mapel }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Hari:</label>
                                <p class="mb-0">{{ $absensiGuru->jadwal->hari }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Jam Mengajar:</label>
                                <p class="mb-0">{{ $absensiGuru->jadwal->jam_mulai }} - {{ $absensiGuru->jadwal->jam_selesai }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tanggal Absensi:</label>
                                <p class="mb-0">{{ \Carbon\Carbon::parse($absensiGuru->tanggal)->format('d/m/Y') }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Status:</label>
                                <p class="mb-0">
                                    @php
                                        $statusColors = [
                                            'Hadir' => 'success',
                                            'Izin' => 'warning',
                                            'Sakit' => 'info',
                                            'Tidak KBM' => 'secondary',
                                            'Tugas' => 'primary'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$absensiGuru->status] ?? 'secondary' }} fs-6">
                                        {{ $absensiGuru->status }}
                                    </span>
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Guru:</label>
                                <p class="mb-0">{{ $absensiGuru->guru->nama }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Dibuat Pada:</label>
                                <p class="mb-0">{{ \Carbon\Carbon::parse($absensiGuru->created_at)->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($absensiGuru->keterangan)
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Keterangan:</label>
                            <div class="p-3 bg-light rounded">
                                <p class="mb-0">{{ $absensiGuru->keterangan }}</p>
                            </div>
                        </div>
                    @endif
                    
                    @if($absensiGuru->materi_yang_diajarkan)
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Materi yang Diajarkan:</label>
                            <div class="p-3 bg-light rounded">
                                <p class="mb-0">{{ $absensiGuru->materi_yang_diajarkan }}</p>
                            </div>
                        </div>
                    @endif

                    @if($absensiGuru->catatan_kbm)
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Catatan KBM:</label>
                            <div class="p-3 bg-light rounded">
                                <p class="mb-0">{{ $absensiGuru->catatan_kbm }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light fw-semibold text-dark">
                    <i class="bi bi-info-circle me-2"></i> Informasi Jadwal
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="bi bi-calendar-event me-2"></i>Jadwal Tetap:</h6>
                        <p class="mb-0 small">
                            Jadwal ini adalah jadwal tetap yang berlaku setiap {{ $absensiGuru->jadwal->hari }} 
                            pada jam {{ $absensiGuru->jadwal->jam_mulai }} - {{ $absensiGuru->jadwal->jam_selesai }}.
                        </p>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-light fw-semibold text-dark">
                    <i class="bi bi-gear me-2"></i> Aksi
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('absensi-guru.edit', $absensiGuru->id) }}" class="btn btn-primary">
                            <i class="bi bi-pencil me-2"></i>Edit Absensi
                        </a>
                        <form action="{{ route('absensi-guru.destroy', $absensiGuru->id) }}" method="POST" 
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus absensi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-trash me-2"></i>Hapus Absensi
                            </button>
                        </form>
                        <a href="{{ route('absensi-guru.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Absensi Siswa -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light fw-semibold text-dark">
                    <i class="bi bi-people me-2"></i> Absensi Siswa
                </div>
                <div class="card-body">
                    @if($absensiSiswa->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Siswa</th>
                                        <th>NIS</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($absensiSiswa as $index => $absensi)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $absensi->siswa->nama }}</td>
                                            <td>{{ $absensi->siswa->nis }}</td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'Hadir' => 'success',
                                                        'Sakit' => 'info',
                                                        'Izin' => 'warning',
                                                        'Alpha' => 'danger'
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $statusColors[$absensi->status] ?? 'secondary' }}">
                                                    @if($absensi->status == 'Hadir') ✅ @endif
                                                    @if($absensi->status == 'Sakit') 🤒 @endif
                                                    @if($absensi->status == 'Izin') 📄 @endif
                                                    @if($absensi->status == 'Alpha') ❌ @endif
                                                    {{ $absensi->status }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($absensi->keterangan)
                                                    <span class="text-muted small">{{ $absensi->keterangan }}</span>
                                                @else
                                                    <span class="text-muted small">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Belum ada data absensi siswa</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 