@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0 fw-bold text-primary">
            <i class="bi bi-plus-circle me-2"></i> Tambah Absensi Guru
        </h1>
        <a href="{{ route('absensi-guru.index') }}" class="btn btn-outline-secondary">
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
                    <i class="bi bi-calendar-plus me-2"></i> Form Absensi Guru
                </div>
                <div class="card-body">
                    <form action="{{ route('absensi-guru.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="jadwal_id" class="form-label">Jadwal Mengajar <span class="text-danger">*</span></label>
                            <select name="jadwal_id" id="jadwal_id" class="form-select @error('jadwal_id') is-invalid @enderror" required>
                                <option value="">Pilih Jadwal</option>
                                @foreach($jadwalHariIni as $jadwal)
                                    <option value="{{ $jadwal->id }}" 
                                            {{ old('jadwal_id', request('jadwal_id')) == $jadwal->id ? 'selected' : '' }}>
                                        {{ $jadwal->kelas->nama }} - {{ $jadwal->mapel }} 
                                        ({{ $jadwal->hari }}, {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }})
                                    </option>
                                @endforeach
                            </select>
                            @error('jadwal_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" id="tanggal" 
                                   class="form-control @error('tanggal') is-invalid @enderror" 
                                   value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="">Pilih Status</option>
                                <option value="Hadir" {{ old('status') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                                <option value="Izin" {{ old('status') == 'Izin' ? 'selected' : '' }}>Izin</option>
                                <option value="Sakit" {{ old('status') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                                <option value="Tidak KBM" {{ old('status') == 'Tidak KBM' ? 'selected' : '' }}>Tidak KBM</option>
                                <option value="Tugas" {{ old('status') == 'Tugas' ? 'selected' : '' }}>Tugas</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="3" 
                                      class="form-control @error('keterangan') is-invalid @enderror" 
                                      placeholder="Masukkan keterangan jika ada">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="materi_yang_diajarkan" class="form-label">Materi yang Diajarkan</label>
                            <textarea name="materi_yang_diajarkan" id="materi_yang_diajarkan" rows="4" 
                                      class="form-control @error('materi_yang_diajarkan') is-invalid @enderror" 
                                      placeholder="Masukkan materi yang diajarkan">{{ old('materi_yang_diajarkan') }}</textarea>
                            @error('materi_yang_diajarkan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="catatan_kbm" class="form-label">Catatan KBM</label>
                            <textarea name="catatan_kbm" id="catatan_kbm" rows="3" 
                                      class="form-control @error('catatan_kbm') is-invalid @enderror" 
                                      placeholder="Catatan tambahan untuk sesi KBM ini (opsional)">{{ old('catatan_kbm') }}</textarea>
                            @error('catatan_kbm')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Daftar Siswa -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0 fw-semibold">
                                    <i class="bi bi-people me-2"></i>Daftar Siswa
                                </h6>
                                <button type="button" class="btn btn-sm btn-success" onclick="setAllHadir()">
                                    <i class="bi bi-check-all me-1"></i>Set Semua Hadir
                                </button>
                            </div>
                            
                            <div id="daftar-siswa" class="border rounded p-3 bg-light">
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-people" style="font-size: 2rem;"></i>
                                    <p class="mb-0 mt-2">Pilih jadwal untuk menampilkan daftar siswa</p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Simpan Absensi
                            </button>
                            <a href="{{ route('absensi-guru.index') }}" class="btn btn-outline-secondary">
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
                            <li>Status "Hadir" untuk kehadiran normal</li>
                            <li>Status "Tidak KBM" untuk jadwal yang tidak dilaksanakan</li>
                            <li>Status "Tugas" untuk pemberian tugas tanpa tatap muka</li>
                            <li>Daftar siswa akan muncul otomatis setelah memilih jadwal</li>
                            <li>Gunakan "Set Semua Hadir" untuk mempercepat input</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h6 class="alert-heading"><i class="bi bi-exclamation-triangle me-2"></i>Perhatian:</h6>
                        <p class="mb-0 small">Absensi hanya dapat diisi untuk jadwal mengajar Anda sendiri dan sesuai dengan hari jadwal.</p>
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
        
        fetch(`/get-siswa-by-jadwal-guru?jadwal_id=${jadwalId}`)
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