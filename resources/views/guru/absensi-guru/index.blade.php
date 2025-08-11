@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0 fw-bold text-primary">
            <i class="bi bi-clipboard-check me-2"></i> Absensi Guru
        </h1>
        <a href="{{ route('absensi-guru.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Tambah Absensi
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Jadwal Mengajar Hari Ini -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light fw-semibold text-dark">
            <i class="bi bi-calendar-event me-2"></i> Jadwal Mengajar Hari Ini
        </div>
        <div class="card-body">
            @if($jadwalMengajar->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                                <th>Hari</th>
                                <th>Jam</th>
                                <th>Status Absensi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jadwalMengajar as $i => $jadwal)
                                @php
                                    $hariIni = \Carbon\Carbon::now()->format('l');
                                    $hariIndonesia = [
                                        'Monday' => 'Senin',
                                        'Tuesday' => 'Selasa',
                                        'Wednesday' => 'Rabu',
                                        'Thursday' => 'Kamis',
                                        'Friday' => 'Jumat',
                                        'Saturday' => 'Sabtu',
                                        'Sunday' => 'Minggu',
                                    ][$hariIni] ?? $hariIni;
                                    
                                    $isHariIni = $jadwal->hari === $hariIndonesia;
                                    $absensiHariIni = $absensiGuru->where('jadwal_id', $jadwal->id)
                                        ->where('tanggal', \Carbon\Carbon::now()->toDateString())
                                        ->first();
                                @endphp
                                
                                @if($isHariIni)
                                    <tr>
                                        <td>{{ $i+1 }}</td>
                                        <td>
                                            <i class="bi bi-easel me-1 text-secondary"></i>
                                            {{ $jadwal->kelas->nama ?? '-' }}
                                        </td>
                                        <td>{{ $jadwal->mapel }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $jadwal->hari }}</span>
                                        </td>
                                        <td>
                                            <i class="bi bi-clock me-1 text-info"></i>
                                            {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                                        </td>
                                        <td>
                                            @if($absensiHariIni)
                                                <span class="badge bg-success">{{ $absensiHariIni->status }}</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Belum Absen</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($absensiHariIni)
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('absensi-guru.show', $absensiHariIni->id) }}" 
                                                       class="btn btn-outline-info" title="Detail">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('absensi-guru.edit', $absensiHariIni->id) }}" 
                                                       class="btn btn-outline-primary" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </div>
                                            @else
                                                <a href="{{ route('absensi-guru.create') }}?jadwal_id={{ $jadwal->id }}" 
                                                   class="btn btn-sm btn-success">
                                                    <i class="bi bi-plus"></i> Absen
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">Tidak ada jadwal mengajar hari ini</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Riwayat Absensi -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light fw-semibold text-dark">
            <i class="bi bi-clock-history me-2"></i> Riwayat Absensi
        </div>
        <div class="card-body">
            @if($absensiGuru->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                                <th>Hari</th>
                                <th>Jam</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($absensiGuru as $i => $absensi)
                                <tr>
                                    <td>{{ $i+1 }}</td>
                                    <td>
                                        <i class="bi bi-calendar me-1 text-secondary"></i>
                                        {{ \Carbon\Carbon::parse($absensi->tanggal)->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        <i class="bi bi-easel me-1 text-secondary"></i>
                                        {{ $absensi->jadwal->kelas->nama ?? '-' }}
                                    </td>
                                    <td>{{ $absensi->jadwal->mapel }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $absensi->jadwal->hari }}</span>
                                    </td>
                                    <td>
                                        <i class="bi bi-clock me-1 text-info"></i>
                                        {{ $absensi->jadwal->jam_mulai }} - {{ $absensi->jadwal->jam_selesai }}
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'Hadir' => 'success',
                                                'Izin' => 'warning',
                                                'Sakit' => 'info',
                                                'Tidak KBM' => 'secondary',
                                                'Tugas' => 'primary'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$absensi->status] ?? 'secondary' }}">
                                            {{ $absensi->status }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($absensi->keterangan)
                                            <span class="text-muted small">{{ Str::limit($absensi->keterangan, 30) }}</span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('absensi-guru.show', $absensi->id) }}" 
                                               class="btn btn-outline-info" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('absensi-guru.edit', $absensi->id) }}" 
                                               class="btn btn-outline-primary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('absensi-guru.destroy', $absensi->id) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus absensi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Hapus">
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
            @else
                <div class="text-center py-4">
                    <i class="bi bi-clock-history text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">Belum ada riwayat absensi</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 