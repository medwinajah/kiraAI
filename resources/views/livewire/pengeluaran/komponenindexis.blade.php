<div class="expense-page">

    {{-- Filter Data Pengeluaran --}}
    <section class="card border-0 shadow-sm rounded-4 mb-3 expense-filter-card">
        <div class="card-body p-3 p-lg-4">

            <div class="d-flex align-items-start justify-content-between
                        flex-wrap gap-2 mb-4">

                <div>
                    <span class="badge rounded-pill bg-success-subtle text-success mb-2 px-3 py-2">
                        <i class="fa-solid fa-filter me-1"></i>
                        Filter Transaksi
                    </span>

                    <h4 class="fw-bold mb-1">
                        Filter Data <span class="text-success">Pengeluaran</span>
                    </h4>

                    <p class="text-secondary small mb-0">
                        Temukan data pengeluaran berdasarkan tanggal atau kata kunci.
                    </p>
                </div>

                <div class="d-flex align-items-center gap-2 text-danger small fw-semibold">
                    <i class="fa-solid fa-wallet"></i>
                    Data Pengeluaran
                </div>

            </div>

            <div class="row g-3 align-items-end">

                {{-- Filter Tanggal --}}
                <div class="col-lg-6">
                    <div class="row g-2">

                        <div class="col-sm-6">
                            <label for="tanggal_mulai_pengeluaran"
                                   class="form-label small fw-semibold">
                                Tanggal Awal
                            </label>

                            <div class="input-group border rounded-3 overflow-hidden bg-white">
                                <span class="input-group-text bg-white border-0 text-success">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </span>

                                <input
                                    type="date"
                                    id="tanggal_mulai_pengeluaran"
                                    wire:model.live.debounce.300ms="tanggal_mulai"
                                    class="form-control border-0 shadow-none py-2"
                                >
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label for="tanggal_selesai_pengeluaran"
                                   class="form-label small fw-semibold">
                                Tanggal Akhir
                            </label>

                            <div class="input-group border rounded-3 overflow-hidden bg-white">
                                <span class="input-group-text bg-white border-0 text-success">
                                    <i class="fa-solid fa-calendar-check"></i>
                                </span>

                                <input
                                    type="date"
                                    id="tanggal_selesai_pengeluaran"
                                    wire:model.live.debounce.300ms="tanggal_selesai"
                                    class="form-control border-0 shadow-none py-2"
                                >
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Pencarian --}}
                <div class="col-lg-5 col-md-9">
                    <label for="search_pengeluaran"
                           class="form-label small fw-semibold">
                        Cari Pengeluaran
                    </label>

                    <div class="input-group border rounded-3 overflow-hidden bg-white">
                        <span class="input-group-text bg-white border-0 text-success">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>

                        <input
                            type="search"
                            id="search_pengeluaran"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Cari label atau deskripsi..."
                            class="form-control border-0 shadow-none py-2"
                        >
                    </div>
                </div>

                {{-- Reset Filter --}}
                <div class="col-lg-1 col-md-3">
                    <label class="form-label small fw-semibold">
                        Reset
                    </label>

                    <button
                        type="button"
                        wire:click="resetProperty"
                        class="btn btn-outline-danger rounded-3 w-100 py-2"
                        title="Reset semua filter"
                        aria-label="Reset filter pengeluaran"
                    >
                        <i class="fa-solid fa-rotate-left"></i>
                    </button>
                </div>

            </div>
        </div>
    </section>


    {{-- Informasi Jumlah Data --}}
    <section class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body p-3">

            <div class="d-flex align-items-center justify-content-between
                        flex-wrap gap-2">

                <div class="d-flex align-items-center gap-3">

                    <div class="rounded-3 bg-danger-subtle text-danger
                                d-flex align-items-center justify-content-center"
                         style="width: 42px; height: 42px; flex-shrink: 0;">
                        <i class="fa-solid fa-money-bill-transfer"></i>
                    </div>

                    <div>
                        <h6 class="fw-bold mb-1">Daftar Pengeluaran</h6>

                        <p class="text-secondary small mb-0">
                            Menampilkan
                            <span class="fw-semibold text-dark">
                                {{ $Pengeluaran->firstItem() ?? 0 }}
                            </span>
                            sampai
                            <span class="fw-semibold text-dark">
                                {{ $Pengeluaran->lastItem() ?? 0 }}
                            </span>
                            dari
                            <span class="fw-semibold text-dark">
                                {{ $Pengeluaran->total() }}
                            </span>
                            data.
                        </p>
                    </div>

                </div>

                <span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-2">
                    <i class="fa-solid fa-database me-1"></i>
                    {{ $Pengeluaran->total() }} Data
                </span>

            </div>
        </div>
    </section>


    {{-- Tabel Data Pengeluaran --}}
    <section class="card border-0 shadow-sm rounded-4 overflow-hidden expense-table-card">

        {{-- Header Tabel --}}
        <div class="card-header bg-white border-0 px-3 px-lg-4 pt-4 pb-3">

            <div class="d-flex align-items-center gap-3">

                <div class="rounded-3 bg-danger-subtle text-danger
                            d-flex align-items-center justify-content-center"
                     style="width: 46px; height: 46px; flex-shrink: 0;">
                    <i class="fa-solid fa-money-bill-transfer fs-5"></i>
                </div>

                <div>
                    <h5 class="fw-bold mb-1">Riwayat Pengeluaran</h5>

                    <p class="text-secondary small mb-0">
                        Kelola dan pantau transaksi uang keluar.
                    </p>
                </div>

            </div>
        </div>


        {{-- Isi Tabel --}}
        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0 expense-table"
                   style="min-width: 1050px;">

                <thead class="table-light">
                    <tr>

                        <th class="text-center text-success small fw-bold py-3 px-3"
                            style="width: 65px;">
                            No.
                        </th>

                        <th class="text-success small fw-bold py-3 px-3"
                            style="min-width: 180px;">
                            LABEL
                        </th>

                        <th class="text-success small fw-bold py-3 px-3"
                            style="min-width: 170px;">
                            KATEGORI
                        </th>

                        <th class="text-success small fw-bold py-3 px-3"
                            style="min-width: 165px;">
                            JUMLAH
                        </th>

                        <th class="text-success small fw-bold py-3 px-3"
                            style="min-width: 135px;">
                            TANGGAL
                        </th>

                        <th class="text-success small fw-bold py-3 px-3"
                            style="min-width: 210px;">
                            DESKRIPSI
                        </th>

                        <th class="text-center text-success small fw-bold py-3 px-3"
                            style="width: 115px;">
                            AKSI
                        </th>

                    </tr>
                </thead>

                <tbody>

                    @forelse ($Pengeluaran as $data)

                        <tr wire:key="pengeluaran-{{ $data->id }}">

                            {{-- Nomor --}}
                            <td class="text-center px-3 py-3">
                                <span class="text-secondary small fw-semibold">
                                    {{ ($Pengeluaran->currentPage() - 1) * $Pengeluaran->perPage() + $loop->iteration }}
                                </span>
                            </td>

                            {{-- Label --}}
                            <td class="px-3 py-3">
                                <div class="fw-semibold text-dark">
                                    {{ $data->label }}
                                </div>
                            </td>

                            {{-- Kategori --}}
                            <td class="px-3 py-3">
                                <span class="badge bg-success-subtle text-success
                                             border border-success-subtle
                                             rounded-pill px-3 py-2 fw-semibold">

                                    <i class="fa-solid fa-tag me-1"></i>

                                    {{ $data->Kategori?->nama_ketegori ?? 'Tanpa kategori' }}

                                </span>
                            </td>

                            {{-- Jumlah Pengeluaran --}}
                            <td class="px-3 py-3">

                                <div class="fw-bold text-danger">
                                    − Rp{{ number_format(
                                        (float) $data->jumlah_pengeluaran,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </div>

                                <div class="text-secondary small mt-1">
                                    <i class="fa-solid fa-arrow-trend-down me-1"></i>
                                    Pengeluaran
                                </div>

                            </td>

                            {{-- Tanggal --}}
                            <td class="px-3 py-3">
                                <div class="d-flex align-items-center gap-2 text-secondary small">

                                    <i class="fa-regular fa-calendar text-success"></i>

                                    <span class="text-nowrap">
                                        {{ $data->tanggal_pengeluaran?->format('d-m-Y') ?? '—' }}
                                    </span>

                                </div>
                            </td>

                            {{-- Deskripsi --}}
                            <td class="px-3 py-3">

                                @if ($data->deskripsi_pengeluaran)
                                    <span
                                        class="d-inline-block text-secondary small text-truncate"
                                        style="max-width: 190px;"
                                        title="{{ $data->deskripsi_pengeluaran }}"
                                    >
                                        {{ $data->deskripsi_pengeluaran }}
                                    </span>
                                @else
                                    <span class="text-secondary small fst-italic">
                                        Tidak ada deskripsi
                                    </span>
                                @endif

                            </td>

                            {{-- Aksi --}}
                            <td class="px-3 py-3">

                                <div class="d-flex justify-content-center
                                            align-items-center gap-2">

                                    {{-- Edit --}}
                                    <a
                                        href="{{ route('pengeluaran.edit', $data->id) }}"
                                        class="btn btn-sm btn-success rounded-3
                                               d-inline-flex align-items-center justify-content-center"
                                        style="width: 35px; height: 35px;"
                                        title="Edit pengeluaran"
                                        aria-label="Edit pengeluaran {{ $data->label }}"
                                    >
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    {{-- Hapus --}}
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-danger rounded-3
                                               d-inline-flex align-items-center justify-content-center"
                                        style="width: 35px; height: 35px;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteusers{{ $data->id }}"
                                        title="Hapus pengeluaran"
                                        aria-label="Hapus pengeluaran {{ $data->label }}"
                                    >
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        {{-- Tampilan Data Kosong --}}
                        <tr>
                            <td colspan="7" class="text-center py-5 px-3">

                                <div class="d-flex flex-column align-items-center">

                                    <div class="rounded-circle bg-success-subtle text-success
                                                d-flex align-items-center justify-content-center mb-3"
                                         style="width: 70px; height: 70px;">
                                        <i class="fa-solid fa-folder-open fa-2x"></i>
                                    </div>

                                    <h6 class="fw-bold mb-2">
                                        {{ $Pengeluaran->total() > 0
                                            ? 'Data Tidak Ditemukan'
                                            : 'Belum Ada Data Pengeluaran' }}
                                    </h6>

                                    <p class="text-secondary small mb-0">
                                        {{ $Pengeluaran->total() > 0
                                            ? 'Coba ubah kata kunci pencarian atau rentang tanggal.'
                                            : 'Data pengeluaran yang kamu tambahkan akan ditampilkan di sini.' }}
                                    </p>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>

        </div>


        {{-- Pagination --}}
        <div class="card-footer bg-white border-top px-3 px-lg-4 py-3">

            <div class="d-flex justify-content-center justify-content-md-end">
                {{ $Pengeluaran->links() }}
            </div>

        </div>

    </section>


    {{-- Modal Hapus --}}
    @include('pengeluaran.partials.modalhapus')

</div>