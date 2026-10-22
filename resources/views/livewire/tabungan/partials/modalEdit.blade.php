<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updatetabunganid{{ $tabungan_id }}" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form wire:submit="update({{ $tabungan_id }})" enctype="multipart/form-data"
            class="modal-content border-0 rounded-4 shadow" style="overflow: hidden;">
            @csrf
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold" id="staticBackdropLabel">Edit Rencana Tabungan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">

                    <section class="mb-3 col-12 d-flex justify-content-center gap-5 shadow-sm rounded-4 py-2 px-2"
                        style="border:2px dotted rgb(8, 166, 87);">
                        {{-- Gambar Tabungan --}}
                        {{-- Gambar Lama --}}
                        <section>
                            <label for="" class="form-label small fw-semibold">Gambar Lama</label>
                            <div style="width: 100px; height: 200px;">
                                @if ($data?->gambar_target_menabung && Storage::disk('public')->exists($data->gambar_target_menabung))
                                    <img src="{{ asset('storage/' . $data->gambar_target_menabung) }}"
                                        alt="Gambar target tabungan" width="100%" height="100%"
                                        style="object-fit: cover;">
                                @else
                                    <div class="d-flex flex-column align-items-center justify-content-center
                bg-light rounded-3 text-secondary"
                                        style="width: 100%; height: 100%; min-height: 150px;">
                                        <i class="fa-regular fa-image fs-2 mb-2"></i>
                                        <small>Belum ada gambar</small>
                                    </div>
                                @endif

                            </div>
                        </section>
                        {{-- Nama Tabungan --}}
                        {{-- Gambar Baru --}}
                        <div>
                            <label for="gambar_baru" class="form-label fw-semibold small mb-2">Gambar Baru</label>

                            {{-- Label dijadikan pembungkus area klik utama --}}
                            <label for="gambar_baru"
                                class="d-block cursor-pointer @error('gambar_baru')
                                    border-danger
                                @enderror"
                                style="width: 150px; height: 200px;">
                                @if ($gambar_baru)
                                    {{-- Box Preview --}}
                                    <div
                                        class="overflow-hidden border rounded-3 position-relative shadow-sm w-100 h-100">
                                        {{-- Perbaikan class w-100 h-100 --}}
                                        <img src="{{ $gambar_baru->temporaryUrl() }}" alt="gambar_baru"
                                            class="w-100 h-100 object-fit-cover img-thumbnail">

                                        {{-- Lapisan overlay tipis saat hover agar estetik --}}
                                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-25 opacity-0 text-white transition-all element-hover-class"
                                            style="transition: 0.2s;">
                                            <i class="fa-solid fa-pen-to-square fs-4"></i>
                                        </div>

                                        {{-- Lapisan Teks Tengah (Akan berada presisi di tengah-tengah gambar) --}}
                                        <div class="position-absolute  w-100 h-100 bg-dark bg-opacity-25"
                                            style="bottom: 2px;">
                                            <span
                                                class="badge text-dark shadow-sm py-2 px-3 fw-semibold small text-wrap">
                                                <i class="fa-solid fa-pen-to-square me-1"></i> Klik untuk Mengganti
                                            </span>
                                        </div>

                                        <button type="button" wire:click="resetGambar"
                                            class="position-absolute btn btn-sm btn-danger d-flex align-items-center justify-content-center shadow"
                                            style="bottom: 8px; right: 8px; z-index: 10; width: 32px; height: 32px; padding: 0; border-radius: 50%;">
                                            <i class="fa-solid fa-xmark fs-6"></i>
                                        </button>
                                    </div>
                                @else
                                    {{-- Box Kosong (Placeholder) --}}
                                    {{-- Menggunakan border-dashed milik Bootstrap (atau border-dotted sesuai style Anda) --}}
                                    <section
                                        class="border border-2 border-dashed d-flex flex-column align-items-center justify-content-center rounded-3 w-100 h-100 text-success shadow-sm header-upload"
                                        style="background-color: rgb(255, 255, 255); cursor: pointer; transition: 0.2s;">
                                        <i class="fa-solid fa-cloud-arrow-up fs-3 mb-2 animate-bounce"></i>
                                        <span class="small fw-semibold">Pilih Gambar</span>
                                    </section>
                                @endif
                            </label>

                            {{-- Input file tersembunyi --}}
                            <input type="file" wire:model.live="gambar_baru" accept="image/*" id="gambar_baru"
                                class="d-none">

                            {{-- Indikator Loading khusus untuk variabel $image --}}
                            <div wire:loading wire:target="gambar_baru" class="small text-muted mt-2">
                                <i class="fa-solid fa-spinner fa-spin text-success"></i> Memproses berkas...
                            </div>

                            @error('gambar_baru')
                                <span class="small text-red">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- =========== --}}

                    </section>

                    <section class="mb-2 col-lg-12 col-sm-12">
                        <label for=""
                            class="form-label @error('label_tabungan')
                    border-danger
                @enderror  small mb-1 fw-semibold"><i
                                class="fa-solid fa-caret-up"></i> Nama Tabungan</label>
                        <div class="input-group border rounded-3">
                            <span class="input-group-text fw-semibold small border-0">
                                <i class="fa-brands fa-rebel"></i>
                            </span>
                            <input type="text" wire:model="label_tabungan"
                                class="form-control p-2 border-0 rounded-4" placeholder="nama tabungan">
                        </div>
                        @error('label_tabungan')
                            <span class="mt-1 fw-light text-danger small">{{ $message }}</span>
                        @enderror
                    </section>

                    {{-- Target Menabung --}}
                    <section class="mb-2 col-lg-6 col-sm-12">
                        <label for="" class="form-label  small mb-1 fw-semibold"><i
                                class="fa-solid fa-caret-up"></i> Target Menabung</label>
                        <div class="input-group border rounded-3">
                            <span class="input-group-text fw-semibold small border-0">
                                Rp.
                            </span>
                            <input type="number" min="1000" name="target_tabungan" wire:model="target_tabungan"
                                class="form-control p-2 border-0 rounded-4 @error('target_tabungan')
                    is-invalid
                @enderror"
                                placeholder="10000">

                        </div>
                        @error('target_tabungan')
                            <span class="mt-1 fw-light text-danger small">{{ $message }}</span>
                        @enderror
                    </section>
                    {{-- Tanggal Awal Menabung --}}
                    <section class="mb-2 col-lg-6 col-sm-12">
                        <label for="" class="form-label  small mb-1 fw-semibold"><i
                                class="fa-solid fa-caret-up"></i> Tanggal Awal Menabung</label>
                        <div
                            class="input-group border @error('tanggal_awal_menabung')
                    border-danger
                @enderror  rounded-3">
                            <span class="input-group-text fw-semibold small border-0">
                                <i class="fa-solid fa-calendar"></i>
                            </span>
                            <input type="date" wire:model="tanggal_awal_menabung"
                                min="{{ now()->format('Y-m-d') }}" name="label_tabungan"
                                class="form-control p-2 border-0 rounded-4" placeholder="10000">
                        </div>
                        @error('tanggal_awal_menabung')
                            <span class="mt-1 fw-light text-danger small">{{ $message }}</span>
                        @enderror
                    </section>

                    {{-- Target Menabung --}}
                    <section class="mb-2 col-lg-12 col-sm-12">
                        <label for="" class="form-label  small mb-1 fw-semibold"><i
                                class="fa-solid fa-caret-up"></i> Tabungan Awal</label>
                        <div
                            class="input-group border @error('tabungan_awal')
                    border-danger
                @enderror rounded-3">
                            <span class="input-group-text fw-semibold small border-0">
                                Rp.
                            </span>
                            <input type="number" name="tabungan_awal" wire:model="tabungan_awal" min="1000"
                                class="form-control p-2 border-0 rounded-4" placeholder="10000">
                        </div>
                        @error('tabungan_awal')
                            <span class="mt-1 fw-light text-danger small">{{ $message }}</span>
                        @enderror
                    </section>

                    {{-- Target Menabung --}}
                    <section class="mb-2 col-lg-12 col-sm-12">
                        <label for="" class="form-label  small mb-1 fw-semibold"><i
                                class="fa-solid fa-caret-up"></i> Deskripsi Tabungan</label>
                        <textarea wire:model="deskripsi" id="" cols="30" rows="4"
                            class="form-control @error('deskripsi')
                    is-invalid
                @enderror"
                            placeholder="Masukan deskripsi tujuan tabungan">{{ $deskripsi }}</textarea>
                        @error('deskripsi')
                            <span class="mt-1 fw-light text-danger small">{{ $message }}</span>
                        @enderror
                    </section>
                </div>
            </div>
            <div class="modal-footer border-top-0 d-flex justify-content-between flex-row flex-nowrap gap-2 mb-2">
                <button type="button" class="btn btn-outline-secondary w-50 fw-semibold rounded-4"
                    data-bs-dismiss="modal">Batal <i class="fa-solid fa-xmark"></i></button>
                <button type="submit" class="btn btn-success w-50 rounded-4 fw-bold">Update <i
                        class="fa-solid fa-plus-circle"></i></button>
            </div>
        </form>
    </div>
</div>
