<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class OrderRecapExport implements
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithEvents,
    WithColumnFormatting,
    WithCustomStartCell
{
    protected Collection $recap;

    protected ?string $dateFrom;

    protected ?string $dateTo;

    protected int $totalWarungs;

    protected int $totalProducts;

    protected float $totalPrice;

    public function __construct(
        Collection $recap,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        int $totalWarungs = 0,
        int $totalProducts = 0,
        float $totalPrice = 0
    ) {
        $this->recap = $recap;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->totalWarungs = $totalWarungs;
        $this->totalProducts = $totalProducts;
        $this->totalPrice = $totalPrice;
    }

    /**
     * Posisi awal header tabel.
     */
    public function startCell(): string
    {
        return 'A5';
    }

    /**
     * Data Excel.
     */
    public function collection(): Collection
    {
        return $this->recap->map(function ($item) {

            $warungDetails = collect($item['warung_details'])
                ->map(function ($warung) use ($item) {

                    $quantity = rtrim(
                        rtrim(
                            number_format(
                                $warung['quantity'],
                                3,
                                ',',
                                '.'
                            ),
                            '0'
                        ),
                        ','
                    );

                    return $warung['warung_name']
                        . ' (' . $quantity . ' ' . $item['unit'] . ')';
                })
                ->implode(', ');

            $totalQuantity = (float) $item['total_quantity'];

            $hargaSatuan = $totalQuantity > 0
                ? $item['total_price'] / $totalQuantity
                : 0;

            return [
                'produk' => $item['product_name'],
                'kode_produk' => $item['product_code'],
                'kategori' => $item['category_name'],
                'warung' => $warungDetails,
                'total_kebutuhan' => $totalQuantity,
                'unit' => $item['unit'],
                'harga_satuan' => $hargaSatuan,
                'total_nilai' => (float) $item['total_price'],
            ];
        });
    }

    /**
     * Header tabel.
     */
    public function headings(): array
    {
        return [
            'Produk',
            'Kode Produk',
            'Kategori',
            'Warung',
            'Total Kebutuhan',
            'Satuan',
            'Harga Satuan',
            'Total Nilai',
        ];
    }

    /**
     * Format angka.
     */
    public function columnFormats(): array
    {
        return [
            'E' => '#,##0.###',
            'G' => '#,##0',
            'H' => '#,##0',
        ];
    }

    /**
     * Styling Excel.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                /*
                |--------------------------------------------------------------------------
                | POSISI DATA
                |--------------------------------------------------------------------------
                */

                $headerRow = 5;
                $dataStartRow = 6;

                $dataEndRow = $dataStartRow + $this->recap->count() - 1;

                $totalRow = max(
                    $dataStartRow,
                    $dataEndRow + 1
                );

                /*
                |--------------------------------------------------------------------------
                | JUDUL
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells('A1:H1');

                $sheet->setCellValue(
                    'A1',
                    'REKAP KEBUTUHAN SEMBAKO'
                );

                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 18,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getRowDimension(1)->setRowHeight(32);

                /*
                |--------------------------------------------------------------------------
                | INFORMASI LAPORAN
                |--------------------------------------------------------------------------
                */

                $sheet->setCellValue('A2', 'Periode');

                $periode = 'Semua tanggal';

                if ($this->dateFrom && $this->dateTo) {
                    $periode = $this->dateFrom . ' s/d ' . $this->dateTo;
                } elseif ($this->dateFrom) {
                    $periode = 'Mulai ' . $this->dateFrom;
                } elseif ($this->dateTo) {
                    $periode = 'Sampai ' . $this->dateTo;
                }

                $sheet->setCellValue('B2', $periode);

                $sheet->setCellValue('A3', 'Tanggal Cetak');

                $sheet->setCellValue(
                    'B3',
                    now()->format('d-m-Y H:i')
                );

                /*
                |--------------------------------------------------------------------------
                | SUMMARY
                |--------------------------------------------------------------------------
                */

                $sheet->setCellValue('D2', 'Jumlah Warung');
                $sheet->setCellValue('E2', $this->totalWarungs);

                $sheet->setCellValue('D3', 'Jumlah Produk');
                $sheet->setCellValue('E3', $this->totalProducts);

                $sheet->setCellValue('G2', 'Total Nilai');
                $sheet->setCellValue('H2', $this->totalPrice);

                /*
                |--------------------------------------------------------------------------
                | STYLE INFORMASI
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle('A2:A3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                ]);

                $sheet->getStyle('D2:D3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                ]);

                $sheet->getStyle('G2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                ]);

                $sheet->getStyle('H2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                ]);

                $sheet->getStyle('H2')
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');

                /*
                |--------------------------------------------------------------------------
                | HEADER TABEL
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle(
                    "A{$headerRow}:H{$headerRow}"
                )->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                    ],

                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],

                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => [
                            'rgb' => 'D9EAF7',
                        ],
                    ],

                    'borders' => [
                        'top' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                        ],
                        'bottom' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                        ],
                        'left' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                        'right' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                $sheet->getRowDimension($headerRow)
                    ->setRowHeight(30);

                /*
                |--------------------------------------------------------------------------
                | BODY
                |--------------------------------------------------------------------------
                */

                if ($this->recap->count() > 0) {

                    $sheet->getStyle(
                        "A{$dataStartRow}:H{$dataEndRow}"
                    )->applyFromArray([
                        'borders' => [
                            'top' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                            'bottom' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                            'left' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                            'right' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],

                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ]);

                    /*
                    | Warung
                    */

                    $sheet->getStyle(
                        "D{$dataStartRow}:D{$dataEndRow}"
                    )->getAlignment()
                        ->setWrapText(true);

                    /*
                    | Quantity
                    */

                    $sheet->getStyle(
                        "E{$dataStartRow}:E{$dataEndRow}"
                    )->getNumberFormat()
                        ->setFormatCode('#,##0.###');

                    $sheet->getStyle(
                        "E{$dataStartRow}:E{$dataEndRow}"
                    )->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_RIGHT
                        );

                    /*
                    | Harga
                    */

                    $sheet->getStyle(
                        "G{$dataStartRow}:H{$dataEndRow}"
                    )->getNumberFormat()
                        ->setFormatCode('#,##0');

                    $sheet->getStyle(
                        "G{$dataStartRow}:H{$dataEndRow}"
                    )->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_RIGHT
                        );
                }

                /*
                |--------------------------------------------------------------------------
                | TOTAL KESELURUHAN
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells(
                    "A{$totalRow}:G{$totalRow}"
                );

                $sheet->setCellValue(
                    "A{$totalRow}",
                    'TOTAL KESELURUHAN NILAI KEBUTUHAN'
                );

                $sheet->setCellValue(
                    "H{$totalRow}",
                    $this->totalPrice
                );

                $sheet->getStyle(
                    "A{$totalRow}:H{$totalRow}"
                )->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                    ],

                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => [
                            'rgb' => 'D9EAF7',
                        ],
                    ],

                    'borders' => [
                        'top' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                        ],
                        'bottom' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                        ],
                        'left' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                        'right' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                $sheet->getStyle(
                    "H{$totalRow}"
                )->getNumberFormat()
                    ->setFormatCode('#,##0');

                $sheet->getStyle(
                    "H{$totalRow}"
                )->getAlignment()
                    ->setHorizontal(
                        Alignment::HORIZONTAL_RIGHT
                    );

                /*
                |--------------------------------------------------------------------------
                | LEBAR KOLOM
                |--------------------------------------------------------------------------
                */

                $sheet->getColumnDimension('A')->setWidth(28);
                $sheet->getColumnDimension('B')->setWidth(16);
                $sheet->getColumnDimension('C')->setWidth(18);
                $sheet->getColumnDimension('D')->setWidth(42);
                $sheet->getColumnDimension('E')->setWidth(18);
                $sheet->getColumnDimension('F')->setWidth(12);
                $sheet->getColumnDimension('G')->setWidth(18);
                $sheet->getColumnDimension('H')->setWidth(20);

                /*
                |--------------------------------------------------------------------------
                | FREEZE
                |--------------------------------------------------------------------------
                */

                $sheet->freezePane('A6');

                /*
                |--------------------------------------------------------------------------
                | PRINT SETTING
                |--------------------------------------------------------------------------
                */

                $sheet->getPageSetup()
                    ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

                $sheet->getPageSetup()
                    ->setPaperSize(PageSetup::PAPERSIZE_A4);

                $sheet->getPageSetup()
                    ->setFitToWidth(1);

                $sheet->getPageSetup()
                    ->setFitToHeight(0);

                /*
                |--------------------------------------------------------------------------
                | MARGIN
                |--------------------------------------------------------------------------
                */

                $sheet->getPageMargins()->setTop(0.4);
                $sheet->getPageMargins()->setRight(0.3);
                $sheet->getPageMargins()->setLeft(0.3);
                $sheet->getPageMargins()->setBottom(0.4);

                /*
                |--------------------------------------------------------------------------
                | PRINT AREA
                |--------------------------------------------------------------------------
                */

                $sheet->getPageSetup()
                    ->setPrintArea("A1:H{$totalRow}");
            },
        ];
    }
}
