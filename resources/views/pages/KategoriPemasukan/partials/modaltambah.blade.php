
{{-- Modal Tambah Kategori --}}
    <div class="modal fade"
         id="modalTambahKategori"
         tabindex="-1"
         aria-labelledby="modalTambahKategoriLabel"
         aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <form action="{{route('kategoripemasukan.store') }}" class="modal-content border-0 rounded-4 shadow" method="POST"
                 style="overflow: hidden;">

                @csrf
                <div class="modal-header text-white"
                     style="background: linear-gradient(135deg, #198754, #116342);">

                    <h5 class="modal-title fw-bold"
                        id="modalTambahKategoriLabel">
                        <i class="fa-solid fa-plus-circle me-2"></i>
                        Tambah Kategori Pemasukan
                    </h5>

                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"
                            aria-label="Tutup">
                    </button>
                </div>

                <div class="modal-body p-4">

                    <div class="mb-3">
                        <label for="nama_kategori"
                               class="form-label small fw-semibold">
                            Nama Kategori
                        </label>

                        <div class="input-group kategori-income-input">
                            <span class="input-group-text">
                                <i class="fa-solid fa-tag"></i>
                            </span>

                            <input type="text"
                                   id="nama_kategori"
                                   name="nama_kategori"
                                   class="form-control @error('nama_kategori')
                                       is-invalid
                                   @enderror"
                                   placeholder="Contoh: Gaji, Bonus, Beasiswa">
                        </div>
                        @error('nama_kategori')
                            <span class="small fw-light text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <label for="deskripsi_kategori"
                               class="form-label small fw-semibold">
                            Deskripsi Kategori
                        </label>

                        <textarea id="deskripsi_kategori"
                                  name="deskripsi_kategori"
                                  class="form-control @error('deskripsi_kategori')
                                       is-invalid
                                   @enderror kategori-income-textarea"
                                  rows="3"
                                  placeholder="Jelaskan kategori pemasukan ini"></textarea>
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