@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-file-earmark-text me-2"></i>Edit Materi
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('materi.update', $materi->id) }}" method="POST" enctype="multipart/form-data">
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
                                                    {{ old('jadwal_id', $materi->jadwal_id) == $jadwal->id ? 'selected' : '' }}>
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
                                    <label for="kelas_id" class="form-label">Kelas <span class="text-danger">*</span></label>
                                    <select class="form-select @error('kelas_id') is-invalid @enderror" 
                                            id="kelas_id" name="kelas_id" required>
                                        <option value="">Pilih Kelas</option>
                                        @if($materi->kelas)
                                            <option value="{{ $materi->kelas_id }}" selected>
                                                {{ $materi->kelas->nama }}
                                            </option>
                                        @endif
                                    </select>
                                    @error('kelas_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="judul" class="form-label">Judul Materi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                                           id="judul" name="judul" value="{{ old('judul', $materi->judul) }}" required>
                                    @error('judul')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="file" class="form-label">File Materi</label>
                                    <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                           id="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.txt">
                                    <small class="form-text text-muted">Format: PDF, DOC, DOCX, PPT, PPTX, TXT (Max: 2MB)</small>
                                    @if($materi->file)
                                        <div class="mt-2">
                                            <small class="text-muted">File saat ini: 
                                                <a href="{{ Storage::url($materi->file) }}" target="_blank">
                                                    {{ basename($materi->file) }}
                                                </a>
                                            </small>
                                        </div>
                                    @endif
                                    @error('file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" name="deskripsi" rows="4">{{ old('deskripsi', $materi->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('materi.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Update Materi
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
    const kelasSelect = document.getElementById('kelas_id');
    
    if (jadwalId) {
        const selectedOption = this.options[this.selectedIndex];
        const kelasText = selectedOption.textContent.match(/-\s*([^-]+)\s*\(/);
        if (kelasText) {
            const kelasName = kelasText[1].trim();
            // Set kelas berdasarkan jadwal yang dipilih
            Array.from(kelasSelect.options).forEach(option => {
                if (option.textContent.includes(kelasName)) {
                    option.selected = true;
                }
            });
        }
    } else {
        kelasSelect.innerHTML = '<option value="">Pilih Kelas</option>';
    }
});
</script>
@endsection 