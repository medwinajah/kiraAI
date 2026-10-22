
{{-- Modal Tambah Kategori Pengeluaran --}}
<div class="modal fade"
     id="modalTambahKategoriPengeluaran"
     tabindex="-1"
     aria-labelledby="modalTambahKategoriPengeluaranLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('kategoripengeluaran.store') }}"
              class="modal-content border-0 rounded-4 shadow"
              method="POST"
              style="overflow: hidden;">

            @csrf

            <div class="modal-header text-white"
                 style="background: linear-gradient(135deg, #198754, #116342);">

                <h5 class="modal-title fw-bold"
                    id="modalTambahKategoriPengeluaranLabel">
                    <i class="fa-solid fa-plus-circle me-2"></i>
                    Tambah Kategori Pengeluaran
                </h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Tutup">
                </button>
            </div>

            <div class="modal-body p-4">

                <div class="mb-3">
                    <label for="nama_kategori_pengeluaran"
                           class="form-label small fw-semibold">
                        Nama Kategori Pengeluaran
                    </label>

                    <div class="input-group kategori-income-input">
                        <span class="input-group-text">
                            <i class="fa-solid fa-tag"></i>
                        </span>

                        <input type="text"
                               id="nama_kategori_pengeluaran"
                               name="nama_kategori"
                               value="{{ old('nama_kategori') }}"
                               class="form-control @error('nama_kategori') is-invalid @enderror"
                               placeholder="Contoh: Makanan, Transportasi, Belanja">
                    </div>

                    @error('nama_kategori')
                        <span class="small fw-light text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="deskripsi_kategori_pengeluaran"
                           class="form-label small fw-semibold">
                        Deskripsi Kategori
                    </label>

                    <textarea id="deskripsi_kategori_pengeluaran"
                              name="deskripsi_kategori"
                              class="form-control kategori-income-textarea @error('deskripsi_kategori') is-invalid @enderror"
                              rows="3"
                              placeholder="Jelaskan kategori pengeluaran ini">{{ old('deskripsi_kategori') }}</textarea>

                    @error('deskripsi_kategori')
                        <span class="small fw-light text-danger">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div class="modal-footer border-0 px-4 pb-4">
                <button type="button"
                        class="btn btn-light border rounded-3 fw-semibold"
                        data-bs-dismiss="modal">
                    Batal
                </button>

                <button type="submit"
                        class="btn text-white rounded-3 fw-semibold"
                        style="background: #198754;">
                    <i class="fa-solid fa-floppy-disk me-1"></i>
                    Simpan Kategori
                </button>
            </div>

        </form>
    </div>
</div>
