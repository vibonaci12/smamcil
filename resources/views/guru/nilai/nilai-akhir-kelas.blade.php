@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0 fw-bold text-primary">
            <i class="bi bi-table me-2"></i> Nilai Akhir Kelas - {{ $kelas->nama ?? 'Kelas' }}
        </h1>
        <a href="{{ route('nilai.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
                    <!-- Class Information -->
                    <div class="mb-4">
                        <h3 class="h5 fw-semibold mb-3">Informasi Kelas</h3>
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Nama Kelas:</strong> {{ $kelas->nama ?? 'Tidak ada data' }}</p>
                                <p><strong>Wali Kelas:</strong> {{ $kelas->waliKelas->nama ?? 'Tidak ada data' }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Jumlah Siswa:</strong> {{ $siswas->count() }}</p>
                                <p><strong>Jumlah Mata Pelajaran:</strong> {{ $jadwals->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Final Grades Table -->
                    <div class="mb-4">
                        <h3 class="h5 fw-semibold mb-3">Nilai Akhir Siswa</h3>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Siswa</th>
                                        <th>NIS</th>
                                        @foreach($jadwals as $jadwalId)
                                            <th class="text-center">
                                                {{ \App\Models\Jadwal::find($jadwalId)->mapel ?? 'Mata Pelajaran' }}
                                            </th>
                                        @endforeach
                                        <th class="text-center">Rata-rata</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($siswas as $index => $siswa)
                                        @php
                                            $totalNilai = 0;
                                            $jumlahNilai = 0;
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $siswa->nama }}</td>
                                            <td>{{ $siswa->nis }}</td>
                                            @foreach($jadwals as $jadwalId)
                                                @php
                                                    $nilaiAkhir = $nilaiAkhir[$siswa->id][$jadwalId]['nilai_akhir'] ?? null;
                                                    if ($nilaiAkhir !== null) {
                                                        $totalNilai += $nilaiAkhir;
                                                        $jumlahNilai++;
                                                    }
                                                @endphp
                                                <td class="text-center">
                                                    @if($nilaiAkhir !== null)
                                                        <span class="fw-semibold">{{ $nilaiAkhir }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                            @php
                                                $rataRata = $jumlahNilai > 0 ? round($totalNilai / $jumlahNilai, 2) : null;
                                            @endphp
                                            <td class="text-center">
                                                @if($rataRata !== null)
                                                    <span class="fw-semibold">{{ $rataRata }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($rataRata !== null)
                                                    <span class="badge 
                                                        @if($rataRata >= 85) bg-success
                                                        @elseif($rataRata >= 75) bg-primary
                                                        @elseif($rataRata >= 60) bg-warning text-dark
                                                        @else bg-danger
                                                        @endif">
                                                        @if($rataRata >= 85) Sangat Baik (A)
                                                        @elseif($rataRata >= 75) Baik (B)
                                                        @elseif($rataRata >= 60) Cukup (C)
                                                        @else Kurang (D)
                                                        @endif
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ 4 + $jadwals->count() }}" class="text-center text-muted py-4">
                                                Belum ada data siswa
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="mb-4">
                        <h3 class="h5 fw-semibold mb-3">Statistik Kelas</h3>
                        <div class="row g-3">
                            @php
                                $totalSiswa = $siswas->count();
                                $siswaDenganNilai = 0;
                                $totalRataRata = 0;
                                $jumlahRataRata = 0;
                                
                                foreach ($siswas as $siswa) {
                                    $totalNilai = 0;
                                    $jumlahNilai = 0;
                                    
                                    foreach ($jadwals as $jadwalId) {
                                        $nilaiAkhir = $nilaiAkhir[$siswa->id][$jadwalId]['nilai_akhir'] ?? null;
                                        if ($nilaiAkhir !== null) {
                                            $totalNilai += $nilaiAkhir;
                                            $jumlahNilai++;
                                        }
                                    }
                                    
                                    if ($jumlahNilai > 0) {
                                        $siswaDenganNilai++;
                                        $rataRata = $totalNilai / $jumlahNilai;
                                        $totalRataRata += $rataRata;
                                        $jumlahRataRata++;
                                    }
                                }
                                
                                $rataRataKelas = $jumlahRataRata > 0 ? round($totalRataRata / $jumlahRataRata, 2) : null;
                            @endphp
                            
                            <div class="col-6 col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">Total Siswa</h6>
                                        <h3 class="mb-0">{{ $totalSiswa }}</h3>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-6 col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">Siswa dengan Nilai</h6>
                                        <h3 class="mb-0">{{ $siswaDenganNilai }}</h3>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-6 col-md-3">
                                <div class="card bg-warning text-dark">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">Rata-rata Kelas</h6>
                                        <h3 class="mb-0">{{ $rataRataKelas ?? '-' }}</h3>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-6 col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">Persentase Lengkap</h6>
                                        <h3 class="mb-0">{{ $totalSiswa > 0 ? round(($siswaDenganNilai / $totalSiswa) * 100, 1) : 0 }}%</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button onclick="printNilaiAkhir()" class="btn btn-primary">
                            <i class="bi bi-printer me-2"></i>Cetak Nilai Akhir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function printNilaiAkhir() {
            window.print();
        }
    </script>
@endsection
