<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('tabungan.store') }}" method="POST" enctype="multipart/form-data" class="modal-content border-0 rounded-4 shadow"
                  style="overflow: hidden;">
            @csrf
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold" id="staticBackdropLabel">Tambah Rencana Tabungan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    {{-- Gambar Tabungan --}}
                    @livewire('tabungan.partials.inputgambar')
                    {{-- Nama Tabungan --}}
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
                            <input type="text" value="{{ old('label_tabungan') }}" name="label_tabungan"
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
                            <input type="number" min="1000" name="target_tabungan"
                                value="{{ old('target_tabungan') }}"
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
                            <input type="date"
                                value="{{ old('target_tabungan') ? old('target_tabungan') : now()->format('Y-m-d') }}"
                                name="tanggal_awal_menabung" min="{{ now()->format('Y-m-d') }}" name="label_tabungan"
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
                            <input type="number" name="tabungan_awal" value="{{ old('tabungan_awal') }}" min="1000"
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
                        <textarea name="deskripsi" id="" cols="30" rows="4"
                            class="form-control @error('deskripsi')
                    is-invalid
                @enderror"
                            placeholder="Masukan deskripsi tujuan tabungan">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <span class="mt-1 fw-light text-danger small">{{ $message }}</span>
                        @enderror
                    </section>
                </div>
            </div>
            <div class="modal-footer border-top-0 d-flex justify-content-between flex-row flex-nowrap gap-2 mb-2">
                <button type="button" class="btn btn-outline-secondary w-50 fw-semibold rounded-4"
                    data-bs-dismiss="modal">Batal <i class="fa-solid fa-xmark"></i></button>
                <button type="submit" class="btn btn-success w-50 rounded-4 fw-bold">Tambah <i
                        class="fa-solid fa-plus-circle"></i></button>
            </div>
        </form>
    </div>
</div>
