<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Laporan Pengeluaran</title>
    {{-- Style mandiri agar laporan cetak tetap rapi tanpa bergantung pada layout utama aplikasi. --}}
    <style>
        body { font-family: Arial, sans-serif; color: #212529; margin: 28px; }
        h1 { margin: 0 0 8px; font-size: 23px; }
        .muted { color: #6c757d; }
        .summary { margin: 20px 0; padding: 14px; border: 1px solid #dee2e6; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { padding: 9px 8px; border: 1px solid #dee2e6; text-align: left; }
        th { background: #e9f5ee; color: #176b49; }
        .number { text-align: right; white-space: nowrap; }
        @media print { body { margin: 0; } .no-print { display: none !important; } }
    </style>
</head>
<body>
    {{-- Tombol manual tersedia apabila browser memblokir pembukaan dialog cetak otomatis. --}}
    <div class="no-print" style="margin-bottom: 18px;">
        <button type="button" onclick="window.print()">Cetak / Simpan sebagai PDF</button>
    </div>

    <h1>Laporan Pengeluaran</h1>
    <p class="muted">Periode: {{ $range['labelPeriode'] }}</p>

    {{-- Tampilkan total nilai yang sama dengan baris transaksi dalam laporan cetak. --}}
    <div class="summary">
        <strong>Total transaksi:</strong> {{ number_format($laporan->count(), 0, ',', '.') }}
        &nbsp; | &nbsp;
        <strong>Total pengeluaran:</strong> Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th><th>Tanggal</th><th>Nama Pengeluaran</th><th>Kategori</th>
                <th>Deskripsi</th><th class="number">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporan as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->tanggal_pengeluaran?->format('d/m/Y H:i') ?? '-' }}</td>
                    <td>{{ $item->label }}</td>
                    <td>{{ $item->Kategori?->nama_ketegori ?? 'Tanpa Kategori' }}</td>

                    <td>{{ $item->deskripsi_pengeluaran ?: '-' }}</td>
                    <td class="number">{{ number_format($item->jumlah_pengeluaran, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;">Tidak ada transaksi pada filter ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Buka dialog cetak setelah halaman cetak selesai dimuat; pilih Save as PDF di browser. --}}
    <script>
        window.addEventListener('load', function () {
            window.print();
        });
    </script>
</body>
</html>
