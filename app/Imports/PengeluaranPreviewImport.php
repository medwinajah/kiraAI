<?php
namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PengeluaranPreviewImport implements WithMultipleSheets
{
    public Collection $rows;

    public function __construct()
    {
        $this->rows = collect();
    }

    /**
     * Membaca worksheet bernama Pengeluaran saja.
     */
    public function sheets(): array
    {
        return [
            'Pengeluaran' => new PengeluaranPreviewSheetImport($this),
        ];
    }
}

class PengeluaranPreviewSheetImport implements ToCollection
{
    public function __construct(
        private PengeluaranPreviewImport $parent
    ) {
    }

    /**
     * Mengambil seluruh baris mentah, termasuk header.
     */
    public function collection(Collection $rows): void
    {
        $this->parent->rows = $rows;
    }
}
