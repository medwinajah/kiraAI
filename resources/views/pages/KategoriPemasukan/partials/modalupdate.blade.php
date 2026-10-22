@foreach ($kategori_pemasukan as $kategori)
    {{-- Modal Update Kategori --}}
    <div class="modal fade"
         id="editKategori{{ $kategori->id }}"
         tabindex="-1"
         aria-labelledby="modalEditKategoriLabel{{ $kategori->id }}"
         aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <!-- Form mengarah ke route update dengan parameter ID -->
            <form action="{{ route('kategoripemasukan.update', $kategori->id) }}" 
                  class="modal-content border-0 rounded-4 shadow" 
                  method="POST"
                  style="overflow: hidden;">

                @csrf
                @method('PUT') {{-- Wajib menggunakan method PUT/PATCH untuk proses update di Laravel --}}
                
                <div class="modal-header text-white"
                      style="background: linear-gradient(135deg, #198754, #116342);"> {{-- Menggunakan aksen warna biru (primary) untuk membedakan dengan modal tambah --}}

                    <h5 class="modal-title fw-bold"
                        id="modalEditKategoriLabel{{ $kategori->id }}">
                        <i class="fa-solid fa-pen-to-square me-2"></i>
                        Edit Kategori Pengeluaran
                    </h5>

                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"
                            aria-label="Tutup">
                    </button>
                </div>

                <div class="modal-body p-4">

                    {{-- Input Nama Kategori --}}
                    <div class="mb-3">
                        <label for="nama_kategori_{{ $kategori->id }}"
                               class="form-label small fw-semibold">
                            Nama Kategori
                        </label>

                        <div class="input-group kategori-income-input">
                            <span class="input-group-text">
                                <i class="fa-solid fa-tag"></i>
                            </span>

                            <input type="text"
                                   id="nama_kategori_{{ $kategori->id }}"
                                   name="nama_kategori"
                                   class="form-control @error('nama_kategori') is-invalid @enderror"
                                   value="{{ old('nama_kategori', $kategori->nama_kategori) }}" {{-- Otomatis terisi data lama --}}
                                   placeholder="Contoh: Gaji, Bonus, Beasiswa"
                                   required>
                        </div>
                        @error('nama_kategori')
                            <span class="small fw-light text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Input Deskripsi Kategori --}}
                    <div class="mb-2">
                        <label for="deskripsi_kategori_{{ $kategori->id }}"
                               class="form-label small fw-semibold">
                            Deskripsi Kategori
                        </label>

                        <textarea id="deskripsi_kategori_{{ $kategori->id }}"
                                  name="deskripsi_kategori"
                                  class="form-control @error('deskripsi_kategori') is-invalid @enderror kategori-income-textarea"
                                  rows="3"
                                  placeholder="Jelaskan kategori pengeluaran ini">{{ old('deskripsi_kategori', $kategori->deskripsi_kategori) }}</textarea>
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
                            style="background: #117057;">
                        <i class="fa-solid fa-floppy-disk me-1"></i>
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
@endforeach
