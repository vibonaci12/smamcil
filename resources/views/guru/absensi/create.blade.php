@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0 fw-bold text-primary">
            <i class="bi bi-clipboard-check me-2"></i> Absensi Terintegrasi
        </h1>
        <a href="{{ route('absensi.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light fw-semibold text-dark">
                    <i class="bi bi-calendar-plus me-2"></i> Form Absensi Siswa
                </div>
                <div class="card-body">
                    <form action="{{ route('absensi.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jadwal_id" class="form-label">Jadwal Mengajar <span class="text-danger">*</span></label>
                                    <select class="form-select @error('jadwal_id') is-invalid @enderror" 
                                            id="jadwal_id" name="jadwal_id" required>
                                        <option value="">Pilih Jadwal</option>
                                        @foreach($jadwals as $jadwal)
                                            <option value="{{ $jadwal->id }}" 
                                                    {{ old('jadwal_id', request('jadwal_id')) == $jadwal->id ? 'selected' : '' }}>
                                                {{ $jadwal->mapel }} - {{ $jadwal->kelas->nama }} ({{ $jadwal->hari }}, {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('jadwal_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                                           id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                    @error('tanggal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Absensi Guru -->
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="bi bi-person-check me-2"></i>Absensi Guru</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status_guru" class="form-label">Status Guru <span class="text-danger">*</span></label>
                                            <select class="form-select @error('status_guru') is-invalid @enderror" 
                                                    id="status_guru" name="status_guru" required>
                                                <option value="">Pilih Status</option>
                                                <option value="Hadir" {{ old('status_guru') == 'Hadir' ? 'selected' : '' }}>✅ Hadir</option>
                                                <option value="Izin" {{ old('status_guru') == 'Izin' ? 'selected' : '' }}>📄 Izin</option>
                                                <option value="Sakit" {{ old('status_guru') == 'Sakit' ? 'selected' : '' }}>🤒 Sakit</option>
                                                <option value="Tidak KBM" {{ old('status_guru') == 'Tidak KBM' ? 'selected' : '' }}>❌ Tidak KBM</option>
                                                <option value="Tugas" {{ old('status_guru') == 'Tugas' ? 'selected' : '' }}>📚 Tugas</option>
                                            </select>
                                            @error('status_guru')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="keterangan_guru" class="form-label">Keterangan Guru</label>
                                            <input type="text" class="form-control @error('keterangan_guru') is-invalid @enderror" 
                                                   id="keterangan_guru" name="keterangan_guru" value="{{ old('keterangan_guru') }}" placeholder="Keterangan (opsional)">
                                            @error('keterangan_guru')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="materi_yang_diajarkan" class="form-label">Materi yang Diajarkan</label>
                                            <textarea class="form-control @error('materi_yang_diajarkan') is-invalid @enderror" 
                                                      id="materi_yang_diajarkan" name="materi_yang_diajarkan" rows="3" 
                                                      placeholder="Masukkan materi yang diajarkan">{{ old('materi_yang_diajarkan') }}</textarea>
                                            @error('materi_yang_diajarkan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="catatan_kbm" class="form-label">Catatan KBM</label>
                                            <textarea class="form-control @error('catatan_kbm') is-invalid @enderror" 
                                                      id="catatan_kbm" name="catatan_kbm" rows="3" 
                                                      placeholder="Catatan tambahan untuk sesi KBM ini">{{ old('catatan_kbm') }}</textarea>
                                            @error('catatan_kbm')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Absensi Siswa -->
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-success text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0"><i class="bi bi-people me-2"></i>Absensi Siswa</h6>
                                    <button type="button" class="btn btn-sm btn-light" onclick="setAllHadir()">
                                        <i class="bi bi-check-all me-1"></i>Set Semua Hadir
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="daftar-siswa" class="border rounded p-3 bg-light">
                                    <div class="text-center text-muted py-4">
                                        <i class="bi bi-people" style="font-size: 2rem;"></i>
                                        <p class="mb-0 mt-2">Pilih jadwal untuk menampilkan daftar siswa</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Simpan Absensi
                            </button>
                            <a href="{{ route('absensi.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light fw-semibold text-dark">
                    <i class="bi bi-info-circle me-2"></i> Informasi
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="bi bi-lightbulb me-2"></i>Petunjuk:</h6>
                        <ul class="mb-0 small">
                            <li>Pilih jadwal mengajar yang sesuai</li>
                            <li>Tanggal harus sesuai dengan hari jadwal</li>
                            <li>Guru otomatis hadir ketika mengisi absensi</li>
                            <li>Daftar siswa muncul otomatis setelah pilih jadwal</li>
                            <li>Gunakan "Set Semua Hadir" untuk efisiensi</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-success">
                        <h6 class="alert-heading"><i class="bi bi-check-circle me-2"></i>Fitur Terintegrasi:</h6>
                        <p class="mb-0 small">Satu form untuk absensi guru dan siswa sekaligus. Lebih efisien dan terintegrasi!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('jadwal_id').addEventListener('change', function() {
    const jadwalId = this.value;
    const daftarSiswa = document.getElementById('daftar-siswa');
    
    if (jadwalId) {
        // Tampilkan loading
        daftarSiswa.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mb-0 mt-2">Memuat daftar siswa...</p>
            </div>
        `;
        
        fetch(`/get-siswa-by-jadwal?jadwal_id=${jadwalId}`)
            .then(response => response.json())
            .then(data => {
                if (data.siswas && data.siswas.length > 0) {
                    let html = '<div class="table-responsive"><table class="table table-sm">';
                    html += '<thead><tr><th>No</th><th>Nama Siswa</th><th>NIS</th><th>Status</th><th>Keterangan</th></tr></thead><tbody>';
                    
                    data.siswas.forEach((siswa, index) => {
                        html += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${siswa.nama}</td>
                                <td>${siswa.nis}</td>
                                <td>
                                    <select name="siswa_absensi[${siswa.id}][status]" class="form-select form-select-sm" style="width: 120px;">
                                        <option value="">Pilih Status</option>
                                        <option value="Hadir">✅ Hadir</option>
                                        <option value="Sakit">🤒 Sakit</option>
                                        <option value="Izin">📄 Izin</option>
                                        <option value="Alpha">❌ Alpha</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="siswa_absensi[${siswa.id}][keterangan]" 
                                           class="form-control form-control-sm" placeholder="Keterangan (opsional)">
                                </td>
                            </tr>
                        `;
                    });
                    
                    html += '</tbody></table></div>';
                    daftarSiswa.innerHTML = html;
                } else {
                    daftarSiswa.innerHTML = `
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-exclamation-triangle" style="font-size: 2rem;"></i>
                            <p class="mb-0 mt-2">Tidak ada siswa di kelas ini</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                daftarSiswa.innerHTML = `
                    <div class="text-center text-danger py-4">
                        <i class="bi bi-exclamation-triangle" style="font-size: 2rem;"></i>
                        <p class="mb-0 mt-2">Terjadi kesalahan saat memuat data</p>
                    </div>
                `;
            });
    } else {
        daftarSiswa.innerHTML = `
            <div class="text-center text-muted py-4">
                <i class="bi bi-people" style="font-size: 2rem;"></i>
                <p class="mb-0 mt-2">Pilih jadwal untuk menampilkan daftar siswa</p>
            </div>
        `;
    }
});

function setAllHadir() {
    const statusSelects = document.querySelectorAll('select[name*="[status]"]');
    statusSelects.forEach(select => {
        select.value = 'Hadir';
    });
}
</script>
@endsection 