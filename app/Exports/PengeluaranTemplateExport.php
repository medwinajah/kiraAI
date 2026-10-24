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

class PengeluaranTemplateExport implements FromArray, WithHeadings, WithTitle, WithEvents
{
    private array $categories;

    public function __construct(array $categories)
    {
        $this->categories = array_values(array_unique(array_filter(
            array_map(
                fn ($category) => trim((string) $category),
                $categories
            ),
            fn ($category) => $category !== ''
        )));
    }

    public function array(): array
    {
        // Template hanya berisi header.
        return [];
    }

    public function headings(): array
    {
        return [
            'tanggal',
            'label',
            'jumlah',
            'kategori',
            'deskripsi',
        ];
    }

    public function title(): string
    {
        return 'Pengeluaran';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                $sheet = $event->sheet->getDelegate();
                $spreadsheet = $sheet->getParent();

                // Sheet daftar kategori untuk dropdown.
                $categorySheet = $spreadsheet->createSheet();
                $categorySheet->setTitle('DaftarKategori');

                foreach ($this->categories as $index => $category) {
                    $categorySheet->setCellValueExplicit(
                        'A' . ($index + 1),
                        $category,
                        DataType::TYPE_STRING
                    );
                }

                $categorySheet->setSheetState(
                    Worksheet::SHEETSTATE_VERYHIDDEN
                );

                // Named range untuk daftar kategori.
                $lastCategoryRow = count($this->categories);

                $spreadsheet->addNamedRange(
                    new NamedRange(
                        'KategoriPengeluaran',
                        $categorySheet,
                        '$A$1:$A$' . $lastCategoryRow
                    )
                );

                // Aturan dropdown pada kolom D.
                $validation = $sheet
                    ->getCell('D2')
                    ->getDataValidation();

                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_STOP);
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);

                // false membuat panah dropdown tetap terlihat di Excel.
                $validation->setShowDropDown(true);

                $validation->setErrorTitle('Kategori tidak valid');
                $validation->setError(
                    'Pilih kategori yang tersedia pada dropdown.'
                );

                $validation->setPromptTitle('Kategori pengeluaran');
                $validation->setPrompt(
                    'Pilih kategori pengeluaran yang sesuai.'
                );

                $validation->setFormula1('=KategoriPengeluaran');

                for ($row = 2; $row <= 1000; $row++) {
                    $sheet->getCell('D' . $row)
                        ->setDataValidation(clone $validation);
                }

                // Tampilan template.
                $sheet->getStyle('A1:E1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => '176B49'],
                    ],
                    'alignment' => [
                        'vertical' => 'center',
                    ],
                ]);

                $sheet->getRowDimension(1)->setRowHeight(28);

                $sheet->getColumnDimension('A')->setWidth(17);
                $sheet->getColumnDimension('B')->setWidth(30);
                $sheet->getColumnDimension('C')->setWidth(18);
                $sheet->getColumnDimension('D')->setWidth(27);
                $sheet->getColumnDimension('E')->setWidth(40);

                $sheet->getStyle('A2:A1000')
                    ->getNumberFormat()
                    ->setFormatCode('yyyy-mm-dd');

                $sheet->getStyle('C2:C1000')
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');

                $sheet->freezePane('A2');

                // Pastikan worksheet yang terlihat saat dibuka adalah Pengeluaran.
                $spreadsheet->setActiveSheetIndex(0);
            },
        ];
    }
}