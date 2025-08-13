@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0 fw-bold text-primary">
            <i class="bi bi-star me-2"></i> Manajemen Nilai
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('nilai.select') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Input Nilai Batch
            </a>
            <div class="dropdown">
                <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-file-earmark-text me-1"></i>Laporan
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="showKelasSelection()">
                        <i class="bi bi-table me-2"></i>Nilai Akhir Kelas
                    </a></li>
                    <li><a class="dropdown-item" href="#" onclick="showSiswaSelection()">
                        <i class="bi bi-person-badge me-2"></i>Transkrip Siswa
                    </a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-semibold text-dark d-flex justify-content-between align-items-center">
            <span><i class="bi bi-star me-2"></i> Daftar Nilai Siswa</span>
        </div>
        <div class="card-body p-0">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    ✅ {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($nilais->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                                <th>Jenis Penilaian</th>
                                <th>Nilai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($nilais as $index => $nilai)
                                <tr>
                                    <td>{{ $nilais->firstItem() + $index }}</td>
                                    <td>
                                        <i class="bi bi-person-circle me-1 text-success"></i>
                                        <strong>{{ $nilai->siswa->nama ?? '-' }}</strong>
                                        <br><small class="text-muted">{{ $nilai->siswa->nis ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <i class="bi bi-easel me-1 text-secondary"></i>
                                        {{ $nilai->jadwal->kelas->nama ?? '-' }}
                                    </td>
                                    <td>
                                        <i class="bi bi-book me-1 text-primary"></i>
                                        {{ $nilai->jadwal->mapel ?? '-' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $nilai->jenisPenilaian->nama_formatted ?? '-' }}
                                            <small class="d-block">{{ $nilai->jenisPenilaian->bobot_formatted ?? '-' }}</small>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $nilai->status }}">
                                            {{ $nilai->nilai_formatted }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('nilai.editBatch', ['jadwal_id' => $nilai->jadwal_id, 'jenis_penilaian_id' => $nilai->jenis_penilaian_id]) }}" 
                                               class="btn btn-sm btn-warning" title="Edit Batch">
                                                <i class="bi bi-pencil"></i> Edit Batch
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($nilais->hasPages())
                    <div class="d-flex justify-content-center p-3">
                        {{ $nilais->links() }}
                    </div>
                @endif
            @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-star display-1"></i>
                    <h5 class="mt-3">Belum ada data nilai</h5>
                    <p>Mulai dengan menginput nilai siswa secara batch.</p>
                    <a href="{{ route('nilai.select') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Input Nilai Batch Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Pilihan Kelas -->
<div class="modal fade" id="kelasModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="kelasSelect" class="form-label">Kelas:</label>
                    <select id="kelasSelect" class="form-select">
                        <option value="">Pilih Kelas...</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="viewNilaiAkhirKelas()">Lihat Nilai Akhir</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pilihan Siswa -->
<div class="modal fade" id="siswaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="siswaSelect" class="form-label">Siswa:</label>
                    <select id="siswaSelect" class="form-select">
                        <option value="">Pilih Siswa...</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="viewTranskripSiswa()">Lihat Transkrip</button>
            </div>
        </div>
    </div>
</div>

<script>
function showKelasSelection() {
    console.log('Loading kelas data...');
    // Fetch kelas data from the server
    fetch('/get-kelas-guru', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Kelas data:', data);
            const select = document.getElementById('kelasSelect');
            select.innerHTML = '<option value="">Pilih Kelas...</option>';
            
            if (data.kelas && data.kelas.length > 0) {
                data.kelas.forEach(kelas => {
                    const option = document.createElement('option');
                    option.value = kelas.id;
                    option.textContent = kelas.nama;
                    select.appendChild(option);
                });
                
                new bootstrap.Modal(document.getElementById('kelasModal')).show();
            } else {
                alert('Tidak ada data kelas yang tersedia');
            }
        })
        .catch(error => {
            console.error('Error loading kelas:', error);
            alert('Terjadi kesalahan saat memuat data kelas: ' + error.message);
        });
}

function showSiswaSelection() {
    console.log('Loading siswa data...');
    // Fetch siswa data from the server
    fetch('/get-siswa-guru', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Siswa data:', data);
            const select = document.getElementById('siswaSelect');
            select.innerHTML = '<option value="">Pilih Siswa...</option>';
            
            if (data.siswa && data.siswa.length > 0) {
                data.siswa.forEach(siswa => {
                    const option = document.createElement('option');
                    option.value = siswa.id;
                    option.textContent = `${siswa.nama} (${siswa.nis}) - ${siswa.kelas?.nama || 'Tidak ada kelas'}`;
                    select.appendChild(option);
                });
                
                new bootstrap.Modal(document.getElementById('siswaModal')).show();
            } else {
                alert('Tidak ada data siswa yang tersedia');
            }
        })
        .catch(error => {
            console.error('Error loading siswa:', error);
            alert('Terjadi kesalahan saat memuat data siswa: ' + error.message);
        });
}

function viewNilaiAkhirKelas() {
    const kelasId = document.getElementById('kelasSelect').value;
    console.log('Selected kelas ID:', kelasId);
    if (kelasId) {
        const url = `/nilai/akhir-kelas/${kelasId}`;
        console.log('Redirecting to:', url);
        window.location.href = url;
    } else {
        alert('Silakan pilih kelas terlebih dahulu');
    }
}

function viewTranskripSiswa() {
    const siswaId = document.getElementById('siswaSelect').value;
    console.log('Selected siswa ID:', siswaId);
    if (siswaId) {
        const url = `/nilai/transkrip/${siswaId}`;
        console.log('Redirecting to:', url);
        window.location.href = url;
    } else {
        alert('Silakan pilih siswa terlebih dahulu');
    }
}
</script>
@endsection 