
@foreach ($kategori_pengeluaran as $kategori)
    <div class="modal fade"
         id="hapusKategori{{ $kategori->id }}"
         tabindex="-1"
         aria-labelledby="judulHapusKategori{{ $kategori->id }}"
         aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow"
                 style="overflow: hidden;">

                {{-- Header --}}
                <div class="modal-header text-white"
                     style="background: linear-gradient(135deg, #198754, #116342);">

                    <h5 class="modal-title fw-bold"
                        id="judulHapusKategori{{ $kategori->id }}">
                        <i class="fa-solid fa-trash-can me-2"></i>
                        Hapus Kategori
                    </h5>

                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"
                            aria-label="Tutup">
                    </button>
                </div>

                {{-- Body --}}
                <div class="modal-body p-4 text-center">

                    <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center"
                         style="width: 65px; height: 65px; background: #fff0f0; color: #dc3545; font-size: 26px;">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>

                    <h5 class="fw-bold mb-2" style="color: #25382d;">
                        Yakin ingin menghapus kategori ini?
                    </h5>

                    <p class="text-secondary small mb-3">
                        Kategori yang akan dihapus:
                    </p>

                    <div class="p-3 rounded-3"
                         style="background: #f2faf4; border: 1px solid #dcebe0;">

                        <div class="fw-bold" style="color: #198754;">
                            <i class="fa-solid fa-tag me-1"></i>
                            {{ $kategori->nama_kategori }}
                        </div>

                        <p class="small text-secondary mb-0 mt-2">
                            {{ $kategori->deskripsi_kategori ?: 'Tidak ada deskripsi kategori.' }}
                        </p>
                    </div>

                    <p class="text-danger small mt-3 mb-0">
                        <i class="fa-solid fa-circle-info me-1"></i>
                        Pastikan kategori ini tidak sedang digunakan oleh transaksi.
                    </p>
                </div>

                {{-- Footer --}}
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button"
                            class="btn btn-light border rounded-3 fw-semibold"
                            data-bs-dismiss="modal">
                        Batal
                    </button>

                    <form action="{{route('kategoripengeluaran.delete',$kategori->id) }}"
                          method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="btn btn-danger rounded-3 fw-semibold">
                            <i class="fa-solid fa-trash-can me-1"></i>
                            Hapus Kategori
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endforeach
