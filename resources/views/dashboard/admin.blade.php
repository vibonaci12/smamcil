@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Judul Halaman -->
    <div class="p-4 rounded shadow-sm mb-4 bg-primary text-white d-flex justify-content-between align-items-center">
        <h1 class="fw-bold mb-0">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard Admin
        </h1>
    </div>

    <!-- Card Statistik -->
    <div class="row g-3 mb-4">
        @php
            $stats = [
                ['count' => $jumlahSiswa, 'label' => 'Siswa Terdaftar', 'icon' => 'bi-people', 'bg' => 'primary'],
                ['count' => $jumlahGuru, 'label' => 'Guru Aktif', 'icon' => 'bi-person-badge', 'bg' => 'success'],
                ['count' => $jumlahKelas, 'label' => 'Total Kelas', 'icon' => 'bi-easel2', 'bg' => 'info'],
                ['count' => $jumlahMapel, 'label' => 'Mata Pelajaran', 'icon' => 'bi-book', 'bg' => 'warning text-dark'],
            ];
        @endphp
        @foreach($stats as $stat)
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100 hover-shadow">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-{{ $stat['bg'] }} rounded-circle p-3 me-3 text-center">
                        <i class="bi {{ $stat['icon'] }} fs-3"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-dark">{{ $stat['count'] }}</div>
                        <div class="text-secondary small">{{ $stat['label'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Jadwal Pelajaran Hari Ini -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-calendar-event me-2 text-primary"></i> Jadwal Pelajaran Hari Ini
            </h5>
            <input type="text" id="searchJadwal" class="form-control form-control-sm w-auto" placeholder="Cari jadwal...">
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="tableJadwal" class="table table-hover table-striped table-bordered align-middle mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru</th>
                            <th>Jam</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwalHariIni as $i => $jadwal)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td><i class="bi bi-easel me-1 text-secondary"></i> {{ $jadwal->kelas->nama ?? '-' }}</td>
                            <td class="fw-semibold text-dark">{{ $jadwal->mapel }}</td>
                            <td><i class="bi bi-person-circle me-1 text-success"></i> {{ $jadwal->guru->nama ?? '-' }}</td>
                            <td><i class="bi bi-clock me-1 text-info"></i> {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                            <td>
                                @if(now()->format('H:i') >= $jadwal->jam_mulai && now()->format('H:i') <= $jadwal->jam_selesai)
                                    <span class="badge bg-success"><i class="bi bi-play-circle me-1"></i> Berlangsung</span>
                                @elseif(now()->format('H:i') > $jadwal->jam_selesai)
                                    <span class="badge bg-secondary"><i class="bi bi-check-circle me-1"></i> Selesai</span>
                                @else
                                    <span class="badge bg-warning text-dark"><i class="bi bi-clock-history me-1"></i> Belum Mulai</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">Tidak ada jadwal hari ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pengumuman Terbaru -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-megaphone me-2 text-warning"></i> Pengumuman Terbaru
            </h5>
            <input type="text" id="searchPengumuman" class="form-control form-control-sm w-auto" placeholder="Cari pengumuman...">
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="tablePengumuman" class="table table-hover table-striped table-bordered align-middle mb-0">
                    <thead class="table-warning">
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Ditujukan Untuk</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengumumanTerbaru as $i => $pengumuman)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td class="fw-semibold text-dark"><i class="bi bi-info-circle me-1 text-primary"></i> {{ $pengumuman->judul }}</td>
                            <td>{{ $pengumuman->kelas->nama ?? 'Umum' }}</td>
                            <td><i class="bi bi-calendar3 me-1 text-secondary"></i> {{ $pengumuman->tanggal }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">Tidak ada pengumuman.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Style tambahan --}}
<style>
    .hover-shadow:hover {
        transform: translateY(-3px);
        transition: 0.2s;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
    }
</style>

{{-- Script Search Filter --}}
<script>
    function tableFilter(inputId, tableId) {
        document.getElementById(inputId).addEventListener("keyup", function () {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll(`#${tableId} tbody tr`);
            rows.forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(filter) ? "" : "none";
            });
        });
    }
    tableFilter("searchJadwal", "tableJadwal");
    tableFilter("searchPengumuman", "tablePengumuman");
</script>
@endsection
