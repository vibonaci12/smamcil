<!-- Modal Create Kelas -->
<div class="modal fade" id="modalCreateKelas" tabindex="-1" aria-labelledby="modalCreateKelasLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-scrollable">
    <div class="modal-content">
      <form action="{{ route('kelas.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title fw-bold" id="modalCreateKelasLabel">Tambah Kelas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama Kelas</label>
            <input type="text" name="nama" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Jurusan</label>
            <input type="text" name="jurusan" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Wali Kelas</label>
            <select name="wali_kelas_id" class="form-select">
              <option value="">-- Pilih Guru --</option>
              @foreach($guruList as $guru)
                <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
