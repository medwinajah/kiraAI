<div>
    {{-- Container Filter Data Tabungan --}}
    <div class="mt-3 overflow-hidden px-3 rounded-4 shadow-sm pb-2">
        <p class="fs-4 ms-2 fw-bold  pt-2 text-success mb-0">FILTER DATA TABUNGAN</p>
        <p class="small text-muted ms-2">Filter data tabungan anda berdasarkan status dan nama label tabungan!</p>
        <div class="row g-2">

            <section class="col-lg-6 col-sm-12">
                <div class=" d-flex overflow-x-auto text-nowrap gap-2 p-2 rounded-4">
                    <section wire:click="$set('status_tabungan','')"
                        class="border px-3 py-1 rounded-pill filter-pill {{ $status_tabungan == '' ? 'active-filter-pill' : '' }}">
                        semua {{ $jumlah_semua }}</section>
                    <section wire:click="$set('status_tabungan','proses')"
                        class="border px-3 py-1 rounded-pill filter-pill {{ $status_tabungan == 'proses' ? 'active-filter-pill' : '' }}">
                        proses {{ $jumlah_proses }}</section>
                    <section wire:click="$set('status_tabungan','sukses')"
                        class="border px-3 py-1 rounded-pill filter-pill {{ $status_tabungan == 'sukses' ? 'active-filter-pill' : '' }}">
                        selesai {{ $jumlah_selesai }}</section>
                </div>
            </section>

            {{-- Search --}}
            <section class="col-lg-6 col-sm-12">
                <input type="search" class="form-control p-2 rounded-4"
                    placeholder="cari berdasarkan nama label tabungan" wire:model.live.debounce.300ms="search"
                    style="border:1px solid rgb(3, 104, 3);">
            </section>

        </div>
    </div>
    {{-- ============================== --}}
    <div class="container-fluid  mt-2 mb-4 p-2">
        <div class="row g-3">
            @forelse ($tabungan as $data)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border rounded-4 overflow-hidden position-relative">

                        <!-- Status Badge (Pojok Kanan Atas) -->
                        <span
                            class="position-absolute top-0 end-0 m-3 badge rounded-pill px-3 py-2 
                            @if ($data->status_tabungan == 'new') bg-info text-dark
                            @elseif($data->status_tabungan == 'proses') bg-warning text-dark
                            @else bg-success text-white @endif">
                            <i class="fa-solid fa-circle-info me-1 small"></i>
                            {{ ucfirst($data->status_tabungan) }}
                        </span>

                        <!-- Gambar Penanda Tabungan -->
                        <div class="ratio ratio-16x9 bg-light">
                            @if ($data->gambar_target_menabung)
                                <img src="{{ asset('storage/' . $data->gambar_target_menabung) }}"
                                    class="card-img-top object-fit-cover" alt="{{ $data->label_tabungan }}">
                            @else
                                <!-- Gambar default jika user tidak upload gambar -->
                                <div class="d-flex align-items-center justify-content-center text-muted flex-column">
                                    <i class="fa-solid fa-piggy-bank fa-3x mb-2 text-secondary"></i>
                                    <span class="small text-uppercase fw-bold tracking-wider fs-7">No Image</span>
                                </div>
                            @endif
                        </div>

                        <!-- Konten Kartu -->
                        <div class="card-body d-flex flex-column p-4">
                            <h5 class="card-title fw-bold text-dark mb-1 text-truncate">
                                {{ $data->label_tabungan }}
                            </h5>
                            <p class="card-text text-muted small mb-3 text-truncate-2-lines">
                                {{ $data->deskripsi ?? 'Tidak ada deskripsi.' }}
                            </p>

                            <!-- Informasi Progress Tabungan -->
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between text-muted small mb-1">
                                    <span>Terkumpul</span>
                                    <span class="fw-semibold text-dark">Rp
                                        {{ number_format($data->total_tabungan, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between text-muted small mb-2">
                                    <span>Target</span>
                                    <span class="fw-bold text-primary">Rp
                                        {{ number_format($data->target_tabungan, 0, ',', '.') }}</span>
                                </div>

                                <!-- Progress Bar (Sekarang memanggil langsung $data->persen dari Controller) -->
                                <div class="progress rounded-pill mb-3" style="height: 8px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated @if ($data->persen == 100) bg-success @else bg-primary @endif"
                                        role="progressbar" style="width: {{ $data->persen }}%;"
                                        aria-valuenow="{{ $data->persen }}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>

                                <!-- Teks persentase tambahan (Opsional jika ingin menampilkan angka % di bawah bar) -->
                                <div class="text-end small text-muted fs-7 mb-2">
                                    {{ number_format($data->persen, 1) }}% Tercapai
                                </div>

                                <div
                                    class="d-flex justify-content-between align-items-center text-muted fs-7 border-top pt-2">
                                    <span><i class="fa-regular fa-calendar-minus me-1"></i> Mulai:
                                        {{ \Carbon\Carbon::parse($data->tanggal_awal_menabung)->format('d M Y') }}</span>
                                    @if ($data->tanggal_target_tercapai)
                                        <span class="text-success fw-medium"><i
                                                class="fa-regular fa-calendar-check me-1"></i> Selesai</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Bagian Tombol Aksi -->
                        <div class="card-footer bg-transparent border-0 px-4 pb-4 pt-0 d-flex gap-2 align-items-center">

                            @if ($data->persen == 100)
                                <button class="btn  btn-success  disabled  btn-sm w-100 rounded-3 py-2 fw-medium">
                                    Tercapai
                                </button>
                            @else
                                <button data-bs-toggle="modal" data-bs-target="#top-up-tabungan{{ $data->id }}"
                                    class="btn btn-outline-primary btn-sm w-100 rounded-3 py-2 fw-medium">
                                    <i class="fa-solid fa-plus-circle me-1"></i> Top Up
                                </button>
                            @endif

                            <div class="dropdown">
                                <!-- Tombol Pemicu Dropdown -->
                                <button class="btn btn-light btn-sm rounded-3 px-3" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical text-muted"></i>
                                </button>

                                <!-- Isi Menu Pilihan Dropdown -->
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-1 fs-7">
                                    <li>
                                        <button class="dropdown-item py-2 d-flex align-items-center text-secondary"
                                            type="button">
                                            <i class="fa-solid fa-eye me-2 text-primary opacity-75"></i> Lihat Detail
                                        </button>
                                    </li>
                                    <li>
                                        <button class="dropdown-item py-2 d-flex align-items-center text-secondary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#updatetabunganid{{ $data->id }}" 
                                            wire:click="edit({{ $data->id }})"
                                            type="button">
                                            <i class="fa-solid fa-pen-to-square me-2 text-warning opacity-75"></i> Ubah
                                            Data
                                        </button>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider opacity-50">
                                    </li>
                                    <li>
                                        <button class="dropdown-item py-2 d-flex align-items-center text-danger"
                                            type="button" data-bs-toggle="modal"
                                            data-bs-target="#deletetabunganid{{ $data->id }}">
                                            <i class="fa-solid fa-trash-can me-2"></i> Hapus Tabungan
                                        </button>
                                    </li>
                                </ul>
                            </div>

                        </div>

                    </div>
                </div>
            @empty
                <!-- Tampilan Jika Data Tabungan Kosong -->
                <div class="col-12 text-center py-5">
                    <div class="py-4">
                        <i class="fa-solid fa-folder-open text-muted fa-4x mb-3 opacity-50"></i>
                        <h5 class="fw-bold text-secondary">Belum Ada Target Tabungan</h5>
                        <p class="text-muted small">Mulai buat rencana tabungan pertamamu untuk melacak impianmu!</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Modal Hapus --}}
    @include('tabungan.partials.modalhapus')
    {{-- Modal TopUp Tabungan --}}
    @include('tabungan.partials.modalTopUp')
    {{-- Modal Update Tabungan
    @include('tabungan.partials.modalEdit')  --}}
</div>
