@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4 fw-bold text-primary"><i class="bi bi-calendar-event me-2"></i> Jadwal Pelajaran</h1>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-semibold text-dark">
            <i class="bi bi-calendar-event me-2"></i> Jadwal Pelajaran Mingguan
        </div>
        <div class="card-body p-0">
            @if($jadwals->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Hari</th>
                                <th>Jam</th>
                                <th>Mata Pelajaran</th>
                                <th>Guru</th>
                                <th>Kelas</th>
                                <th>Materi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jadwals as $index => $jadwal)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $jadwal->hari }}</span>
                                    </td>
                                    <td>
                                        <i class="bi bi-clock me-1 text-info"></i>
                                        <strong>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</strong>
                                    </td>
                                    <td>
                                        <i class="bi bi-book me-1 text-primary"></i>
                                        <strong>{{ $jadwal->mapel }}</strong>
                                    </td>
                                    <td>
                                        <i class="bi bi-person-badge me-1 text-success"></i>
                                        <strong>{{ $jadwal->guru->nama ?? '-' }}</strong>
                                    </td>
                                    <td>
                                        <i class="bi bi-easel me-1 text-secondary"></i>
                                        <span class="badge bg-secondary">{{ $jadwal->kelas->nama }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('siswa.materi') }}?mapel={{ $jadwal->mapel }}" 
                                           class="btn btn-sm btn-outline-primary" title="Lihat Materi">
                                            <i class="bi bi-file-earmark-text"></i> Materi
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-calendar-x display-1"></i>
                    <h5 class="mt-3">Belum ada jadwal pelajaran</h5>
                    <p>Jadwal pelajaran akan muncul setelah admin menambahkan jadwal untuk kelas Anda.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 