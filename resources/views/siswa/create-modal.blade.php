<!-- Modal Create Siswa -->
<div class="modal fade" id="modalCreateSiswa" tabindex="-1" aria-labelledby="modalCreateSiswaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <form action="{{ route('siswa.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title fw-bold" id="modalCreateSiswaLabel">Tambah Siswa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">NIS <span class="text-danger">*</span></label>
              <input type="text" name="nis" class="form-control" required maxlength="8" minlength="8" 
                     pattern="[0-9]{8}" placeholder="12345678" 
                     oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8)">
              <small class="text-muted">NIS harus tepat 8 digit angka (contoh: 12345678)</small>
            </div>
            <div class="col-md-6">
              <label class="form-label">Nama <span class="text-danger">*</span></label>
              <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Kelas <span class="text-danger">*</span></label>
              <select name="kelas_id" class="form-select" required>
                <option value="">Pilih Kelas</option>
                @foreach($kelasList as $kelas)
                  <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
              <select name="jenis_kelamin" class="form-select" required>
                <option value="">Pilih Jenis Kelamin</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
              <input type="date" name="tanggal_lahir" class="form-control" required>
              <small class="text-muted">Akan digunakan sebagai password awal (format: YYYYMMDD)</small>
            </div>
            <div class="col-md-6">
              <label class="form-label">Jurusan</label>
              <input type="text" name="jurusan" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label">No HP</label>
              <input type="text" name="no_hp" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label">Alamat</label>
              <textarea name="alamat" class="form-control" rows="2"></textarea>
            </div>
            <div class="col-md-6">
              <label class="form-label">Foto</label>
              <input type="file" name="foto" class="form-control" accept="image/*">
            </div>
          </div>
        </div>
        <div class="modal-footer d-flex justify-content-between">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
