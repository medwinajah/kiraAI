<div>
    {{-- Filter Data --}}
    <div class="row p-lg-4 p-2 py-4 mt-2 border rounded-4 shadow-sm g-2">
        <section>
            <h3 class="fw-bold small fs-4">Filter Data <span class="text-success"> Pengeluaran </span></h3>
            <p class="small fw-light">Filter data pengeluaran yang pernah anda buat sebelumnya.</p>
        </section>
        <section class="col-lg-6 col-sm-12">
            <div class="d-flex gap-2 overflow-x-auto pb-2 hide-scrollbar">
                <section>
                    <lable for="" class="form-label small fw-semibold">Awal</lable>
                    <div class="input-group border rounded-3">
                        <span class="input-group-text border-0">
                            <i class="fa-solid fa-calendar-days"></i>
                        </span>
                        <input type="date" wire:model.live.debounce.300ms="tanggal_mulai"
                            class="form-control p-2 border-0">
                    </div>
                </section>
                <section>
                    <lable for="" class="form-label small fw-semibold">Akhir</lable>
                    <div class="input-group border rounded-3">
                        <span class="input-group-text border-0">
                            <i class="fa-solid fa-calendar-days"></i>
                        </span>
                        <input type="date" wire:model.live.debounce.300ms="tanggal_selesai"
                            class="form-control p-2 border-0">
                    </div>
                </section>
            </div>
        </section>

        <section class="col-lg-6 col-sm-12">
            <div class="row g-2 align-items-center">
                <section class="col-lg-11 col-10">
                    <lable class="form-lable small fw-semibold">Search</lable>
                    <div class="input-group border rounded-3">
                        <span class="input-group-text border-0">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search"
                            class="form-control p-2 border-0">
                    </div>
                </section>

                <section class="col-lg-1 col-2">
                    <label for="" class="form-label small fw-semibold mb-0">Reset</label>
                    <div class="input-group">
                        <button wire:click="resetProperty" class="btn border rounded btn-outline-danger w-sm-100"><i
                                class="fa-solid fa-xmark"></i></button>
                    </div>
                </section>
            </div>
        </section>
    </div>



    {{-- Table Data --}}
    <section class="mt-2">
        <section class=" border rounded-4 mt-2 p-2 mb-2 row g-2 align-items-center">
            <section class="col-lg-6 col-sm-12">
                <p class="mb-0 mt-0 small">Menampilkan: <span class="fw-semibold">
                        {{ $Pengeluaran->firstItem() }}</span> sampai <span
                        class="fw-semibold">{{ $Pengeluaran->lastItem() }}</span> data dari total <span
                        class="fw-semibold">{{ $Pengeluaran->total() }}</span> data. </p>
            </section>
        </section>

        <div class="card p-2 shadow-sm rounded-4 pb-0">

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="pb-2">
                        <tr class="pb-2">
                            <th class="col-1 text-center pb-3 bg-head-table  text-white">No</th>
                            <th class="col-2 text-center pb-3 bg-head-table  text-white">Label</th>
                            <th class="col-2 text-center pb-3 bg-head-table  text-white">Kategori</th>
                            <th class="col-2 text-center pb-3 bg-head-table  text-white">Jumlah</th>
                            <th class="col-2 text-center pb-3 bg-head-table  text-white">Tanggal</th>
                            <th class="col-2 text-center pb-3 bg-head-table  text-white">Deskripsi</th>
                            <th class="col-2 text-center pb-3 bg-head-table  text-white">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($Pengeluaran as $data)
                            <tr class="pt-2">
                                <td class="col-1 align-middle text-center pb-3 fw-semibold small">
                                    {{ ($Pengeluaran->currentPage() - 1) * $Pengeluaran->perPage() + $loop->iteration }}
                                </td>
                                <td class="col-2 align-middle text-center pb-3 fw-semibold small">{{ $data->label }}
                                </td>
                                <td class="col-2 align-middle text-center pb-3 fw-light small">
                                    {{ $data->Kategori->nama_ketegori }}</td>
                                <td class="col-2 align-middle text-center pb-3 fw-semibold small "><span
                                        class="badge bg-success shadow">Rp.
                                        {{ number_format($data->jumlah_pengeluaran, 0, ',', '.') }}</span></td>
                                <td class="col-2 align-middle text-center pb-3 fw-light small">
                                    {{ $data->tanggal_pengeluaran->format('Y-m-d') }}</td>
                                <td class="col-2 align-middle text-center pb-3 small fw-light">
                                    {{ $data->deskripsi_pengeluaran == '' ? ' - ' : $data->deskripsi_pengeluaran }}</td>
                                <td
                                    class="col-2 align-middle text-center pb-3 d-flex align-items-center justify-content-center gap-2 p-4 w-100">

                                    <!-- Button trigger modal Hapus-->
                                    <button type="button" class="btn btn-danger small fw-semibold"
                                        data-bs-toggle="modal" data-bs-target="#deleteusers{{ $data->id }}"><i
                                            class="fa-solid fa-trash"></i>
                                    </button>

                                    <form action="{{ route('pengeluaran.edit', $data->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-info small fw-semibold"><i
                                                class="fa-solid fa-pen-to-square"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center fw-semibold p-4 pt-5 pb-5">Data pengeluaran Masih
                                    Kosong!</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
            {{--  --}}
            <div class="card-footer d-flex align-items-center mt-3 pt-3">
                <div class="d-flex justify-content-between flex-column flex-md-row align-items-center gap-2">
                    {{ $Pengeluaran->links() }}
                </div>
            </div>
        </div>
    </section>
    {{-- Modal Hapus --}}
    @include('pengeluaran.partials.modalhapus')
</div>
