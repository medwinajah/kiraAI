@extends('Layouts.app')

@section('title', 'Laporan Pengeluaran')

@section('link_css')
    {{-- Muat CSS khusus halaman laporan pengeluaran menggunakan Vite. --}}
    @vite(['resources/css/LaporanPengeluaran/index.css'])
@endsection

@section('content')
<div class="laporan-pengeluaran-page container-fluid px-3 px-lg-4 py-4">
    {{-- Header halaman dan tombol unduh laporan mengikuti filter aktif. --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="small text-muted mb-2">
                <i class="fa-solid fa-house me-1"></i> Dashboard
                <i class="fa-solid fa-chevron-right mx-2" style="font-size: 10px;"></i> Laporan
                <i class="fa-solid fa-chevron-right mx-2" style="font-size: 10px;"></i>
                <span class="text-success fw-semibold">Pengeluaran</span>
            </div>
            <h3 class="fw-bold mb-2">Laporan Pengeluaran</h3>
            <p class="text-muted mb-0">Pantau dan evaluasi pengeluaran berdasarkan periode yang kamu pilih.</p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            {{-- Ekspor Excel mengunduh CSV yang kompatibel dengan Excel sesuai filter aktif. --}}
            <a href="{{ route('laporanpengeluaran.export.excel', request()->query()) }}"
               class="btn btn-outline-success rounded-3 px-3">
                <i class="fa-solid fa-file-excel me-2"></i>Ekspor Excel
            </a>
            {{-- Halaman cetak dibuka di tab baru agar dapat dicetak atau disimpan sebagai PDF. --}}
            <a href="{{ route('laporanpengeluaran.export.pdf', request()->query()) }}" target="_blank" rel="noopener"
               class="btn btn-success rounded-3 px-3">
                <i class="fa-solid fa-file-pdf me-2"></i>Cetak / PDF
            </a>
        </div>
    </div>

    {{-- Form filter dikirim dengan GET supaya pilihan periode dapat dibagikan dan dipertahankan saat paginasi. --}}
    <form method="GET" action="{{ route('laporanpengeluaran.index') }}" class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3 p-lg-4">
            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="laporan-icon-filter"><i class="fa-solid fa-sliders"></i></div>
                <div>
                    <h6 class="fw-bold mb-1">Filter Laporan</h6>
                    <small class="text-muted">Pilih periode dan kategori pengeluaran.</small>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-12 col-sm-6 col-lg-3">
                    <label for="periode" class="form-label small fw-semibold">Periode Laporan</label>
                    <select id="periode" name="periode" class="form-select">
                        <option value="harian" @selected($range['periode'] === 'harian')>Harian</option>
                        <option value="mingguan" @selected($range['periode'] === 'mingguan')>Mingguan</option>
                        <option value="bulanan" @selected($range['periode'] === 'bulanan')>Bulanan</option>
                        <option value="tahunan" @selected($range['periode'] === 'tahunan')>Tahunan</option>
                        <option value="custom" @selected($range['periode'] === 'custom')>Rentang Tanggal</option>
                    </select>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <label for="tanggalAwal" class="form-label small fw-semibold">Dari Tanggal</label>
                    <input type="date" id="tanggalAwal" name="tanggal_awal" class="form-control"
                           value="{{ $range['tanggalAwal'] }}">
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <label for="tanggalAkhir" class="form-label small fw-semibold">Sampai Tanggal</label>
                    <input type="date" id="tanggalAkhir" name="tanggal_akhir" class="form-control"
                           value="{{ $range['tanggalAkhir'] }}">
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <label for="kategori" class="form-label small fw-semibold">Kategori Pengeluaran</label>
                    <select id="kategori" name="kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategoriPengeluaran as $kategoriItem)
                            <option value="{{ $kategoriItem->id }}" @selected((string) request('kategori') === (string) $kategoriItem->id)>
                                {{ $kategoriItem->nama_ketegori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Pertahankan kata pencarian ketika filter periode atau kategori diterapkan. --}}
                <input type="hidden" name="search" value="{{ request('search') }}">

                <div class="col-12 d-flex flex-wrap justify-content-end gap-2 mt-3">
                    {{-- Reset mengembalikan filter ke bulan berjalan dan menghapus pencarian/kategori. --}}
                    <a href="{{ route('laporanpengeluaran.index') }}" class="btn btn-light border rounded-3 px-3">
                        <i class="fa-solid fa-rotate-left me-2"></i>Reset
                    </a>
                    <button type="submit" class="btn btn-success rounded-3 px-4">
                        <i class="fa-solid fa-filter me-2"></i>Terapkan Filter
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- Kartu ringkasan menggunakan hasil agregasi database, bukan data hard-coded. --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card laporan-summary-card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <p class="small text-muted fw-semibold mb-2">Total Pengeluaran</p>
                            <h4 class="fw-bold mb-0">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h4>
                        </div>
                        <div class="laporan-summary-icon bg-success-subtle text-success"><i class="fa-solid fa-wallet"></i></div>
                    </div>
                    <div class="small text-muted mt-4"><i class="fa-solid fa-calendar-days me-1"></i>{{ $range['labelPeriode'] }}</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card laporan-summary-card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <p class="small text-muted fw-semibold mb-2">Jumlah Transaksi</p>
                            <h4 class="fw-bold mb-0">{{ number_format($jumlahTransaksi, 0, ',', '.') }} <span class="fs-6 fw-normal text-muted">transaksi</span></h4>
                        </div>
                        <div class="laporan-summary-icon bg-primary-subtle text-primary"><i class="fa-solid fa-receipt"></i></div>
                    </div>
                    <div class="small text-muted mt-4">Sesuai periode dan kategori terpilih</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card laporan-summary-card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <p class="small text-muted fw-semibold mb-2">Rata-rata Pengeluaran</p>
                            <h4 class="fw-bold mb-0">Rp {{ number_format($rataRataPengeluaran, 0, ',', '.') }}</h4>
                        </div>
                        <div class="laporan-summary-icon bg-info-subtle text-info"><i class="fa-solid fa-chart-line"></i></div>
                    </div>
                    <div class="small text-muted mt-4">Rata-rata per transaksi</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card laporan-summary-card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <p class="small text-muted fw-semibold mb-2">Pengeluaran Terbesar</p>
                            <h4 class="fw-bold mb-0">Rp {{ number_format($pengeluaranTerbesar, 0, ',', '.') }}</h4>
                        </div>
                        <div class="laporan-summary-icon bg-warning-subtle text-warning"><i class="fa-solid fa-arrow-trend-up"></i></div>
                    </div>
                    <div class="small text-muted mt-4">Nilai transaksi tertinggi</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik tren dibangun dari transaksi yang cocok dengan periode dan kategori. --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3 p-lg-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                        <div>
                            <h6 class="fw-bold mb-1">Tren Pengeluaran</h6>
                            <p class="small text-muted mb-0">Pengelompokan otomatis berdasarkan periode laporan.</p>
                        </div>
                        <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2">
                            <i class="fa-solid fa-chart-column me-1"></i>{{ ucfirst($range['periode'] === 'custom' ? 'rentang tanggal' : $range['periode']) }}
                        </span>
                    </div>

                    @if (collect($grafikPengeluaran)->sum('jumlah') > 0)
                        {{-- Lebar kolom mengikuti jumlah titik grafik; area dapat digeser untuk banyak titik. --}}
                        <div class="laporan-chart-scroll">
                            <div class="laporan-chart-grid" style="--jumlah-kolom: {{ count($grafikPengeluaran) }};">
                                @foreach ($grafikPengeluaran as $item)
                                    <div class="laporan-chart-column" title="{{ $item['label'] }}: Rp {{ number_format($item['jumlah'], 0, ',', '.') }}">
                                        <span class="small text-muted mb-2">{{ number_format($item['jumlah'] / 1000000, 2, ',', '.') }} jt</span>
                                        <div class="laporan-chart-track">
                                            <div class="laporan-chart-bar" style="height: {{ $item['tinggi'] }}%;"></div>
                                        </div>
                                        <span class="small text-muted mt-2 text-center">{{ $item['label'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        {{-- Kondisi grafik kosong ditampilkan ketika belum ada transaksi pada filter aktif. --}}
                        <div class="laporan-empty-chart text-center py-5">
                            <i class="fa-solid fa-chart-column fs-1 text-success mb-3"></i>
                            <p class="fw-semibold mb-1">Belum ada data grafik</p>
                            <span class="small text-muted">Tidak ditemukan pengeluaran untuk periode dan kategori ini.</span>
                        </div>
                    @endif

                    <div class="d-flex align-items-center gap-2 small text-muted mt-4">
                        <span class="laporan-chart-legend"></span>Total pengeluaran per kelompok waktu
                    </div>
                </div>
            </div>
        </div>

        {{-- Ringkasan di samping grafik juga menggunakan data database yang telah difilter. --}}
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3 p-lg-4">
                    <h6 class="fw-bold mb-1">Informasi Laporan</h6>
                    <p class="small text-muted mb-4">Ringkasan dari transaksi yang sesuai dengan filter.</p>

                    <div class="laporan-info-item">
                        <div class="laporan-info-icon"><i class="fa-solid fa-calendar-check"></i></div>
                        <div><small class="text-muted d-block">Periode</small><span class="fw-semibold">{{ $range['labelPeriode'] }}</span></div>
                    </div>
                    <div class="laporan-info-item">
                        <div class="laporan-info-icon"><i class="fa-solid fa-layer-group"></i></div>
                        <div><small class="text-muted d-block">Kategori teratas</small><span class="fw-semibold">{{ $kategoriTeratas }}</span></div>
                    </div>
                    <div class="laporan-info-item mb-0">
                        <div class="laporan-info-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
                        <div>
                            <small class="text-muted d-block">Transaksi terakhir</small>
                            <span class="fw-semibold">{{ $transaksiTerakhir?->tanggal_pengeluaran?->format('d/m/Y H:i') ?? 'Belum ada transaksi' }}</span>
                        </div>
                    </div>

                    <hr class="my-4">
                    <div class="p-3 rounded-3 bg-success-subtle">
                        <div class="d-flex align-items-start gap-2">
                            <i class="fa-solid fa-lightbulb text-success mt-1"></i>
                            <div>
                                <p class="fw-semibold text-success mb-1">Tips Keuangan</p>
                                <p class="small mb-0 text-dark">Catat transaksi secara rutin agar laporan pengeluaran dan evaluasi keuangan tetap akurat.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel transaksi mendukung pencarian dan paginasi server-side. --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 p-3 p-lg-4">
                <div>
                    <h6 class="fw-bold mb-1">Detail Transaksi Pengeluaran</h6>
                    <p class="small text-muted mb-0">Daftar transaksi sesuai periode, kategori, dan kata pencarian.</p>
                </div>

                {{-- Form pencarian mempertahankan filter periode dan kategori yang sedang aktif. --}}
                <form method="GET" action="{{ route('laporanpengeluaran.index') }}" class="input-group laporan-search">
                    <input type="hidden" name="periode" value="{{ $range['periode'] }}">
                    <input type="hidden" name="tanggal_awal" value="{{ $range['tanggalAwal'] }}">
                    <input type="hidden" name="tanggal_akhir" value="{{ $range['tanggalAkhir'] }}">
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="search" name="search" class="form-control border-start-0 ps-0"
                           placeholder="Cari transaksi..." aria-label="Cari transaksi" value="{{ request('search') }}">
                    <button class="btn btn-outline-success" type="submit">Cari</button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0 laporan-table">
                    <thead>
                        <tr>
                            <th class="ps-4">No.</th>
                            <th>Tanggal</th>
                            <th>Nama Pengeluaran</th>
                            <th>Kategori</th>
                            <th>Deskripsi</th>
                            <th class="text-end pe-4">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($laporan as $index => $item)
                            <tr>
                                <td class="ps-4 text-muted">{{ $laporan->firstItem() + $index }}</td>
                                <td><span class="text-nowrap">{{ $item->tanggal_pengeluaran?->format('d/m/Y H:i') ?? '-' }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="laporan-transaction-icon"><i class="fa-solid fa-arrow-up"></i></div>
                                        <span class="fw-semibold">{{ $item->label }}</span>
                                    </div>
                                </td>
                                <td><span class="badge rounded-pill bg-light text-dark border px-3 py-2">{{ $item->Kategori?->nama_ketegori ?? 'Tanpa Kategori' }}</span></td>
                                <td class="text-muted laporan-description">{{ $item->deskripsi_pengeluaran ?: '-' }}</td>
                                <td class="text-end pe-4"><span class="fw-bold text-danger text-nowrap">− Rp {{ number_format($item->jumlah_pengeluaran, 0, ',', '.') }}</span></td>
                            </tr>
                        @empty
                            {{-- Pesan kosong muncul jika tidak ada transaksi yang memenuhi filter/pencarian. --}}
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                        <!-- Wrapper d-flex diletakkan di dalam td -->
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <i class="fa-solid fa-folder-open fs-1 text-muted mb-3"></i>
                                            <p class="fw-semibold mb-1">Belum ada data pengeluaran</p>
                                            <span class="text-muted small">Coba ubah periode, kategori, atau kata pencarian.</span>
                                        </div>
                                    </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 px-3 px-lg-4 py-3 border-top">
                <small class="text-muted">
                    Menampilkan {{ $laporan->firstItem() ?? 0 }}–{{ $laporan->lastItem() ?? 0 }} dari {{ $laporan->total() }} transaksi
                </small>
                {{-- Tautan paginasi tetap membawa parameter filter dan pencarian. --}}
                <div>{{ $laporan->onEachSide(1)->links('pagination::bootstrap-5') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
