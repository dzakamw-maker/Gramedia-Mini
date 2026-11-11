<?php

namespace App\Exports;

use App\Models\SubscriptionPackage;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SubscriptionPackagesExport implements FromCollection, ShouldAutoSize, WithCustomStartCell, WithHeadings, WithMapping, WithStyles
{
    private int $no = 0;

    
    public function startCell(): string
    {
        return 'A2';
    }

    
    public function headings(): array
    {
        return [
            'Nomor',
            'Nama Paket Langganan',
            'Harga',
            'Jumlah Buku',
            'Pelanggan Aktif',
        ];
    }

    
    public function collection(): Collection
    {
        return SubscriptionPackage::with([
            'subscriptionPackageBooks',
            'subscriptionPackageUsers' => function ($query) {
                $query->whereDate('expired_date', '>=', now()->toDateString());
            },
        ])->get();
    }

    public function map($subscriptionPackage): array
    {
        return [
            ++$this->no,
            $subscriptionPackage->name_package,
            'Rp ' . number_format((int) $subscriptionPackage->price, 0, ',', '.'),
            $subscriptionPackage->subscriptionPackageBooks->count(),
            $subscriptionPackage->subscriptionPackageUsers->count(),
        ];
    }

    
    public function styles(Worksheet $sheet): array
    {
        $totalRows = $sheet->getHighestRow();

        $sheet->setCellValue('A1', 'Data Paket Langganan dengan Buku dan Pengguna');
        $sheet->mergeCells('A1:E1');
        $sheet->getRowDimension(1)->setRowHeight(28);

        return [
            'A1:E1' => [
                'font' => [
                    'bold' => true,
                    'size' => 18,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            'A2:E2' => [
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'D9D9D9',
                    ],
                ],
            ],
            'A2:E' . $totalRows => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ],
        ];
    }
}
