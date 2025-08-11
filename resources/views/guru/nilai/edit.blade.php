@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-star me-2"></i>Edit Nilai
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('nilai.update', $nilai->id) }}" method="POST">
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
                                                    {{ old('jadwal_id', $nilai->jadwal_id) == $jadwal->id ? 'selected' : '' }}>
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
                                        @if($nilai->siswa)
                                            <option value="{{ $nilai->siswa_id }}" selected>
                                                {{ $nilai->siswa->nama }} ({{ $nilai->siswa->nis }})
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
                                    <label for="jenis" class="form-label">Jenis Nilai <span class="text-danger">*</span></label>
                                    <select class="form-select @error('jenis') is-invalid @enderror" 
                                            id="jenis" name="jenis" required>
                                        <option value="">Pilih Jenis</option>
                                        <option value="Tugas" {{ old('jenis', $nilai->jenis) == 'Tugas' ? 'selected' : '' }}>Tugas</option>
                                        <option value="UTS" {{ old('jenis', $nilai->jenis) == 'UTS' ? 'selected' : '' }}>UTS</option>
                                        <option value="UAS" {{ old('jenis', $nilai->jenis) == 'UAS' ? 'selected' : '' }}>UAS</option>
                                    </select>
                                    @error('jenis')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nilai" class="form-label">Nilai <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('nilai') is-invalid @enderror" 
                                           id="nilai" name="nilai" min="0" max="100" 
                                           value="{{ old('nilai', $nilai->nilai) }}" required>
                                    @error('nilai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('nilai.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Update Nilai
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
        fetch(`/get-siswa-by-jadwal-nilai?jadwal_id=${jadwalId}`)
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