<!-- Modal Create Jadwal -->
<div class="modal fade" id="modalCreateJadwal" tabindex="-1" aria-labelledby="modalCreateJadwalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <form action="{{ route('jadwal.store') }}" method="POST">
        @csrf
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title fw-bold" id="modalCreateJadwalLabel">
            <i class="bi bi-plus-circle me-2"></i> Tambah Jadwal Baru
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <!-- Notifikasi Error -->
          @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <ul class="mb-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="kelas_id" class="form-label fw-semibold">
                <i class="bi bi-easel me-1 text-primary"></i> Kelas
              </label>
              <select class="form-select @error('kelas_id') is-invalid @enderror" name="kelas_id" required>
                <option value="">Pilih Kelas</option>
                @foreach($kelasList as $kelas)
                  <option value="{{ $kelas->id }}" @selected(old('kelas_id') == $kelas->id)>
                    {{ $kelas->nama }}
                  </option>
                @endforeach
              </select>
              @error('kelas_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-3">
              <label for="mapel" class="form-label fw-semibold">
                <i class="bi bi-book me-1 text-info"></i> Mata Pelajaran
              </label>
              <input type="text" class="form-control @error('mapel') is-invalid @enderror" 
                name="mapel" value="{{ old('mapel') }}" placeholder="Contoh: Matematika" required>
              @error('mapel')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="guru_id" class="form-label fw-semibold">
                <i class="bi bi-person-badge me-1 text-success"></i> Guru
              </label>
              <select class="form-select @error('guru_id') is-invalid @enderror" name="guru_id" required>
                <option value="">Pilih Guru</option>
                @foreach($guruList as $guru)
                  <option value="{{ $guru->id }}" @selected(old('guru_id') == $guru->id)>
                    {{ $guru->nama }}
                  </option>
                @endforeach
              </select>
              @error('guru_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-3">
              <label for="hari" class="form-label fw-semibold">
                <i class="bi bi-calendar-week me-1 text-secondary"></i> Hari
              </label>
              <select class="form-select @error('hari') is-invalid @enderror" name="hari" required>
                <option value="">Pilih Hari</option>
                @foreach($hariList as $hari)
                  <option value="{{ $hari }}" @selected(old('hari') == $hari)>
                    {{ $hari }}
                  </option>
                @endforeach
              </select>
              @error('hari')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="jam_mulai" class="form-label fw-semibold">
                <i class="bi bi-clock me-1 text-warning"></i> Jam Mulai (24 Jam)
              </label>
              <input type="time" class="form-control @error('jam_mulai') is-invalid @enderror" 
                name="jam_mulai" value="{{ old('jam_mulai') }}" 
                step="900" min="00:00" max="23:59" 
                placeholder="07:00" required>
              <small class="form-text text-muted">Format 24 jam (contoh: 07:00, 13:30, 15:45)</small>
              @error('jam_mulai')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-3">
              <label for="jam_selesai" class="form-label fw-semibold">
                <i class="bi bi-clock-fill me-1 text-danger"></i> Jam Selesai (24 Jam)
              </label>
              <input type="time" class="form-control @error('jam_selesai') is-invalid @enderror" 
                name="jam_selesai" value="{{ old('jam_selesai') }}" 
                step="900" min="00:00" max="23:59" 
                placeholder="08:30" required>
              <small class="form-text text-muted">Format 24 jam (contoh: 08:30, 14:30, 16:45)</small>
              @error('jam_selesai')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <!-- Info Tambahan -->
          <div class="alert alert-info border-0 bg-light">
            <i class="bi bi-info-circle me-2 text-info"></i>
            <strong>Tips:</strong> 
            <ul class="mb-0 mt-2">
              <li>Gunakan format 24 jam (00:00 - 23:59)</li>
              <li>Jam mulai harus sebelum jam selesai</li>
              <li>Pastikan jadwal tidak bentrok dengan jadwal kelas yang sama pada hari yang sama</li>
            </ul>
          </div>
        </div>
        <div class="modal-footer d-flex justify-content-between">
          <button type="button" class="btn btn-secondary shadow-sm" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Batal
          </button>
          <button type="submit" class="btn btn-success shadow-sm">
            <i class="bi bi-save me-1"></i> Simpan Jadwal
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
