@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-primary">
            <i class="bi bi-table me-2"></i> Input Nilai Batch
        </h1>
        <a href="{{ route('nilai.select') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <!-- Info Header -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-info text-white">
            <h6 class="mb-0">
                <i class="bi bi-info-circle me-2"></i> Informasi Input Nilai
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>Kelas:</strong> {{ $jadwal->kelas->nama }}
                </div>
                <div class="col-md-3">
                    <strong>Mata Pelajaran:</strong> {{ $jadwal->mapel }}
                </div>
                <div class="col-md-3">
                    <strong>Jenis Penilaian:</strong> {{ ucfirst($jenisPenilaian->nama) }}
                </div>
                <div class="col-md-3">
                    <strong>Bobot:</strong> {{ $jenisPenilaian->bobot }}%
                </div>
            </div>
        </div>
    </div>

    <!-- Batch Input Form -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-table me-2"></i> Tabel Input Nilai Siswa
            </h5>
            <span class="badge bg-light text-dark">{{ $jadwal->kelas->siswas->count() }} Siswa</span>
        </div>
        <div class="card-body p-0">
            <form action="{{ route('nilai.storeBatch') }}" method="POST">
                @csrf
                <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
                <input type="hidden" name="jenis_penilaian_id" value="{{ $jenisPenilaian->id }}">
                
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 25%">Nama Siswa</th>
                                <th style="width: 15%">NIS</th>
                                <th style="width: 20%">Nilai Saat Ini</th>
                                <th style="width: 25%">Input Nilai Baru</th>
                                <th style="width: 10%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwal->kelas->siswas as $index => $siswa)
                                @php
                                    $existingNilai = $existingNilai->get($siswa->id);
                                    $currentNilai = $existingNilai ? $existingNilai->nilai : null;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <i class="bi bi-person-circle me-1 text-success"></i>
                                        <strong>{{ $siswa->nama }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $siswa->nis }}</span>
                                    </td>
                                    <td>
                                        @if($currentNilai !== null)
                                            <span class="badge bg-{{ $currentNilai >= 75 ? 'success' : ($currentNilai >= 60 ? 'warning' : 'danger') }}">
                                                {{ number_format($currentNilai, 1) }}
                                            </span>
                                        @else
                                            <span class="badge bg-light text-dark">Belum ada nilai</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" 
                                                class="form-control nilai-input" 
                                                name="nilai[{{ $siswa->id }}]" 
                                                value="{{ $currentNilai }}"
                                                min="0" 
                                                max="100" 
                                                step="0.1"
                                                placeholder="0-100"
                                                onchange="validateNilai(this)">
                                            <span class="input-group-text">/ 100</span>
                                        </div>
                                        <small class="form-text text-muted">
                                            Range: 0.0 - 100.0
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        @if($currentNilai !== null)
                                            <span class="badge bg-info">Update</span>
                                        @else
                                            <span class="badge bg-success">Baru</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="bi bi-people display-4"></i>
                                        <h6 class="mt-2">Tidak ada siswa dalam kelas ini</h6>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($jadwal->kelas->siswas->count() > 0)
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                <small>
                                    <i class="bi bi-info-circle me-1"></i>
                                    Gunakan <strong>updateOrCreate</strong> untuk efisiensi data
                                </small>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-secondary" onclick="clearAllNilai()">
                                    <i class="bi bi-eraser me-1"></i> Clear All
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i> Simpan Semua Nilai
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Tips Section -->
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-warning text-dark">
            <h6 class="mb-0">
                <i class="bi bi-lightbulb me-2"></i> Tips Input Nilai
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="fw-semibold">Validasi Nilai:</h6>
                    <ul class="mb-0">
                        <li>Range nilai: 0.0 - 100.0</li>
                        <li>Bisa menggunakan desimal (contoh: 85.5)</li>
                        <li>Kosongkan field untuk tidak mengubah nilai</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-semibold">Fitur Batch:</h6>
                    <ul class="mb-0">
                        <li>Input semua nilai sekaligus</li>
                        <li>Auto-update nilai yang sudah ada</li>
                        <li>Buat nilai baru jika belum ada</li>
                        <li>Hanya untuk kelas yang diajar</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .nilai-input:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    .nilai-input.invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
    
    .table th {
        position: sticky;
        top: 0;
        z-index: 10;
    }
</style>

<script>
    function validateNilai(input) {
        const value = parseFloat(input.value);
        const min = parseFloat(input.min);
        const max = parseFloat(input.max);
        
        if (input.value === '') {
            input.classList.remove('invalid');
            return true;
        }
        
        if (isNaN(value) || value < min || value > max) {
            input.classList.add('invalid');
            input.setCustomValidity('Nilai harus antara 0-100');
            return false;
        } else {
            input.classList.remove('invalid');
            input.setCustomValidity('');
            return true;
        }
    }
    
    function clearAllNilai() {
        if (confirm('Yakin ingin mengosongkan semua input nilai?')) {
            document.querySelectorAll('.nilai-input').forEach(input => {
                input.value = '';
                input.classList.remove('invalid');
            });
        }
    }
    
    // Form validation before submit
    document.querySelector('form').addEventListener('submit', function(e) {
        let isValid = true;
        const inputs = document.querySelectorAll('.nilai-input');
        
        inputs.forEach(input => {
            if (input.value !== '' && !validateNilai(input)) {
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Mohon periksa input nilai yang tidak valid.');
        }
    });
</script>
@endsection
