@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0 fw-bold text-primary">
            <i class="bi bi-pencil me-2"></i> Edit Absensi Guru
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
                    <i class="bi bi-calendar-edit me-2"></i> Edit Absensi Guru
                </div>
                <div class="card-body">
                    <form action="{{ route('absensi-guru.update', $absensiGuru->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Jadwal Mengajar</label>
                            <input type="text" class="form-control" value="{{ $absensiGuru->jadwal->kelas->nama }} - {{ $absensiGuru->jadwal->mapel }} ({{ $absensiGuru->jadwal->hari }}, {{ $absensiGuru->jadwal->jam_mulai }} - {{ $absensiGuru->jadwal->jam_selesai }})" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($absensiGuru->tanggal)->format('d/m/Y') }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="">Pilih Status</option>
                                <option value="Hadir" {{ old('status', $absensiGuru->status) == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                                <option value="Izin" {{ old('status', $absensiGuru->status) == 'Izin' ? 'selected' : '' }}>Izin</option>
                                <option value="Sakit" {{ old('status', $absensiGuru->status) == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                                <option value="Tidak KBM" {{ old('status', $absensiGuru->status) == 'Tidak KBM' ? 'selected' : '' }}>Tidak KBM</option>
                                <option value="Tugas" {{ old('status', $absensiGuru->status) == 'Tugas' ? 'selected' : '' }}>Tugas</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="3" 
                                      class="form-control @error('keterangan') is-invalid @enderror" 
                                      placeholder="Masukkan keterangan jika ada">{{ old('keterangan', $absensiGuru->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="materi_yang_diajarkan" class="form-label">Materi yang Diajarkan</label>
                            <textarea name="materi_yang_diajarkan" id="materi_yang_diajarkan" rows="4" 
                                      class="form-control @error('materi_yang_diajarkan') is-invalid @enderror" 
                                      placeholder="Masukkan materi yang diajarkan">{{ old('materi_yang_diajarkan', $absensiGuru->materi_yang_diajarkan) }}</textarea>
                            @error('materi_yang_diajarkan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="catatan_kbm" class="form-label">Catatan KBM</label>
                            <textarea name="catatan_kbm" id="catatan_kbm" rows="3" 
                                      class="form-control @error('catatan_kbm') is-invalid @enderror" 
                                      placeholder="Catatan tambahan untuk sesi KBM ini (opsional)">{{ old('catatan_kbm', $absensiGuru->catatan_kbm ?? '') }}</textarea>
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
                            
                            <div class="border rounded p-3 bg-light">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Siswa</th>
                                                <th>NIS</th>
                                                <th>Status</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($siswaList as $index => $siswa)
                                                @php
                                                    $absensiSiswa = $absensiSiswa->get($siswa->id);
                                                @endphp
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $siswa->nama }}</td>
                                                    <td>{{ $siswa->nis }}</td>
                                                    <td>
                                                        <select name="siswa_absensi[{{ $siswa->id }}][status]" class="form-select form-select-sm" style="width: 120px;">
                                                            <option value="">Pilih Status</option>
                                                            <option value="Hadir" {{ $absensiSiswa && $absensiSiswa->status == 'Hadir' ? 'selected' : '' }}>✅ Hadir</option>
                                                            <option value="Sakit" {{ $absensiSiswa && $absensiSiswa->status == 'Sakit' ? 'selected' : '' }}>🤒 Sakit</option>
                                                            <option value="Izin" {{ $absensiSiswa && $absensiSiswa->status == 'Izin' ? 'selected' : '' }}>📄 Izin</option>
                                                            <option value="Alpha" {{ $absensiSiswa && $absensiSiswa->status == 'Alpha' ? 'selected' : '' }}>❌ Alpha</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="siswa_absensi[{{ $siswa->id }}][keterangan]" 
                                                               class="form-control form-control-sm" 
                                                               placeholder="Keterangan (opsional)"
                                                               value="{{ $absensiSiswa ? $absensiSiswa->keterangan : '' }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Update Absensi
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
                    <i class="bi bi-info-circle me-2"></i> Informasi Absensi
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kelas:</label>
                        <p class="mb-0">{{ $absensiGuru->jadwal->kelas->nama }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mata Pelajaran:</label>
                        <p class="mb-0">{{ $absensiGuru->jadwal->mapel }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Hari & Jam:</label>
                        <p class="mb-0">{{ $absensiGuru->jadwal->hari }}, {{ $absensiGuru->jadwal->jam_mulai }} - {{ $absensiGuru->jadwal->jam_selesai }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Absensi:</label>
                        <p class="mb-0">{{ \Carbon\Carbon::parse($absensiGuru->tanggal)->format('d/m/Y') }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Saat Ini:</label>
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
                            <span class="badge bg-{{ $statusColors[$absensiGuru->status] ?? 'secondary' }}">
                                {{ $absensiGuru->status }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function setAllHadir() {
    const statusSelects = document.querySelectorAll('select[name*="[status]"]');
    statusSelects.forEach(select => {
        select.value = 'Hadir';
    });
}
</script>
@endsection 