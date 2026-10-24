<?php
namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\NamedRange;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransaksiTemplateExport implements FromArray, WithHeadings, WithTitle, WithEvents
{
    private array $categories;

    public function __construct(array $categories)
    {
        $this->categories = array_values(
            array_unique(
                array_filter(
                    array_map(
                        fn ($category) => trim((string) $category),
                        $categories
                    ),
                    fn ($category) => $category !== ''
                )
            )
        );
    }

    /**
     * Template hanya berisi header, tanpa transaksi contoh.
     */
    public function array(): array
    {
        return [];
    }

    /**
     * Header yang digunakan oleh proses previewImport().
     */
    public function headings(): array
    {
        return [
            'tanggal',
            'label',
            'jumlah',
            'kategori',
            'sumber_pemasukan',
            'deskripsi',
        ];
    }

    public function title(): string
    {
        return 'Pemasukan';
    }

    /**
     * Membuat dropdown kategori berdasarkan data database.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                $sheet = $event->sheet->getDelegate();
                $spreadsheet = $sheet->getParent();

                /*
                 * 1. Membuat sheet khusus untuk daftar kategori.
                 * Sheet ini disembunyikan dari pengguna.
                 */
                $categorySheet = $spreadsheet->createSheet();
                $categorySheet->setTitle('DaftarKategori');

                foreach ($this->categories as $index => $category) {
                    $row = $index + 1;

                    $categorySheet->setCellValueExplicit(
                        'A' . $row,
                        $category,
                        DataType::TYPE_STRING
                    );
                }

                $categorySheet->setSheetState(
                    Worksheet::SHEETSTATE_VERYHIDDEN
                );

                /*
                 * 2. Membuat named range yang menunjuk ke daftar kategori.
                 * Named range bisa dipakai oleh dropdown di sheet utama.
                 */
                $lastCategoryRow = count($this->categories);

                $spreadsheet->addNamedRange(
                    new NamedRange(
                        'KategoriPemasukan',
                        $categorySheet,
                        '$A$1:$A$' . $lastCategoryRow
                    )
                );

                /*
                 * 3. Membuat aturan dropdown pada kolom D (kategori).
                 */
                $validation = $sheet
                    ->getCell('D2')
                    ->getDataValidation();

                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(
                    DataValidation::STYLE_STOP
                );

                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);

                $validation->setErrorTitle('Kategori tidak valid');
                $validation->setError(
                    'Pilih kategori yang tersedia pada daftar dropdown.'
                );

                $validation->setPromptTitle('Pilih kategori pemasukan');
                $validation->setPrompt(
                    'Klik dropdown dan pilih kategori pemasukan yang sesuai.'
                );

                $validation->setFormula1('=KategoriPemasukan');

                /*
                 * 4. Menyiapkan dropdown untuk baris 2 sampai 1000.
                 * Pengguna dapat mengisi banyak transaksi.
                 */
                for ($row = 2; $row <= 1000; $row++) {
                    $sheet->getCell('D' . $row)
                        ->setDataValidation(clone $validation);
                }

                /*
                 * 5. Merapikan tampilan template.
                 */
                $sheet->getStyle('A1:F1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => [
                            'rgb' => 'FFFFFF',
                        ],
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => [
                            'rgb' => '176B49',
                        ],
                    ],
                    'alignment' => [
                        'vertical' => 'center',
                    ],
                ]);

                $sheet->getRowDimension(1)->setRowHeight(28);

                $sheet->getColumnDimension('A')->setWidth(16);
                $sheet->getColumnDimension('B')->setWidth(30);
                $sheet->getColumnDimension('C')->setWidth(18);
                $sheet->getColumnDimension('D')->setWidth(25);
                $sheet->getColumnDimension('E')->setWidth(25);
                $sheet->getColumnDimension('F')->setWidth(35);

                $sheet->getStyle('A2:A1000')
                    ->getNumberFormat()
                    ->setFormatCode('yyyy-mm-dd');

                $sheet->getStyle('C2:C1000')
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');

                $sheet->freezePane('A2');
            },
        ];
    }
}
