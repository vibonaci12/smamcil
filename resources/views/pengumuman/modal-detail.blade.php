<!-- Modal Detail Pengumuman -->
<div class="modal fade" id="modalDetailPengumuman{{ $pengumuman->id }}" tabindex="-1" aria-labelledby="modalDetailPengumumanLabel{{ $pengumuman->id }}" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="modalDetailPengumumanLabel{{ $pengumuman->id }}">Detail Pengumuman</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <h5 class="fw-bold mb-2">{{ $pengumuman->judul }}</h5>
        <p class="text-muted"><i class="bi bi-calendar-event me-1"></i> {{ $pengumuman->tanggal->format('d F Y') }}</p>
        <hr>
        <p>{{ $pengumuman->isi }}</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
