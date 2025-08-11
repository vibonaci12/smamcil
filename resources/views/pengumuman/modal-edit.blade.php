<!-- Modal Edit Pengumuman -->
<div class="modal fade" id="modalEditPengumuman{{ $pengumuman->id }}" tabindex="-1" aria-labelledby="modalEditPengumumanLabel{{ $pengumuman->id }}" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <form action="{{ route('pengumuman.update', $pengumuman->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title fw-bold" id="modalEditPengumumanLabel{{ $pengumuman->id }}">Edit Pengumuman</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" name="judul" class="form-control" value="{{ $pengumuman->judul }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ $pengumuman->tanggal->format('Y-m-d') }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Isi</label>
            <textarea name="isi" class="form-control" rows="4" required>{{ $pengumuman->isi }}</textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning"><i class="bi bi-save me-1"></i> Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
