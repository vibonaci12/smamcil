@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clipboard-check me-2"></i>Edit Absensi
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('absensi.update', $absensi->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jadwal_id" class="form-label">Jadwal <span class="text-danger">*</span></label>
                                    <select class="form-select @error('jadwal_id') is-invalid @enderror" 
                                            id="jadwal_id" name="jadwal_id" required>
                                        <option value="">Pilih Jadwal</option>
                                        @foreach($jadwals as $jadwal)
                                            <option value="{{ $jadwal->id }}" 
                                                    {{ old('jadwal_id', $absensi->jadwal_id) == $jadwal->id ? 'selected' : '' }}>
                                                {{ $jadwal->mapel }} - {{ $jadwal->kelas->nama }} ({{ $jadwal->hari }})
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
                                    <label for="siswa_id" class="form-label">Siswa <span class="text-danger">*</span></label>
                                    <select class="form-select @error('siswa_id') is-invalid @enderror" 
                                            id="siswa_id" name="siswa_id" required>
                                        <option value="">Pilih Siswa</option>
                                        @if($absensi->siswa)
                                            <option value="{{ $absensi->siswa_id }}" selected>
                                                {{ $absensi->siswa->nama }} ({{ $absensi->siswa->nis }})
                                            </option>
                                        @endif
                                    </select>
                                    @error('siswa_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                                           id="tanggal" name="tanggal" 
                                           value="{{ old('tanggal', $absensi->tanggal->format('Y-m-d')) }}" required>
                                    @error('tanggal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="">Pilih Status</option>
                                        <option value="Hadir" {{ old('status', $absensi->status) == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                                        <option value="Sakit" {{ old('status', $absensi->status) == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                                        <option value="Izin" {{ old('status', $absensi->status) == 'Izin' ? 'selected' : '' }}>Izin</option>
                                        <option value="Alpha" {{ old('status', $absensi->status) == 'Alpha' ? 'selected' : '' }}>Alpha</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                      id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $absensi->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('absensi.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Update Absensi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('jadwal_id').addEventListener('change', function() {
    const jadwalId = this.value;
    const siswaSelect = document.getElementById('siswa_id');
    
    if (jadwalId) {
        fetch(`/get-siswa-by-jadwal?jadwal_id=${jadwalId}`)
            .then(response => response.json())
            .then(data => {
                siswaSelect.innerHTML = '<option value="">Pilih Siswa</option>';
                data.siswas.forEach(siswa => {
                    const option = document.createElement('option');
                    option.value = siswa.id;
                    option.textContent = `${siswa.nama} (${siswa.nis})`;
                    siswaSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error:', error);
            });
    } else {
        siswaSelect.innerHTML = '<option value="">Pilih Siswa</option>';
    }
});
</script>
@endsection 