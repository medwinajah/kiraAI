<?php
namespace App\Imports;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TransaksiPreviewImport implements WithMultipleSheets
{
    /**
     * Menyimpan seluruh baris dari worksheet Pemasukan.
     */
    public Collection $rows;

    public function __construct()
    {
        $this->rows = collect();
    }

    /**
     * Hanya membaca worksheet pertama (indeks 0),
     * yaitu worksheet Pemasukan.
     *
     * Worksheet DaftarKategori tidak akan dibaca.
     */
    public function sheets(): array
    {
        return [
            0 => new TransaksiPreviewSheetImport($this),
        ];
    }
}

/**
 * Importer khusus untuk worksheet yang dipilih.
 */
class TransaksiPreviewSheetImport implements ToCollection
{
    public function __construct(
        private TransaksiPreviewImport $parent
    ) {
    }

    /**
     * Membaca semua baris mentah, termasuk header.
     */
    public function collection(Collection $rows): void
    {
        $this->parent->rows = $rows;
    }
}