<?php

namespace App\Exports;

use public\storage\covers;
use App\Models\BookCategory;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;


class BookCategoriesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    private $no = 0; //inisialisasi variabel no dengan nilai 0
    public function headings(): array
    {
        return [
            'No',
            'Nama Kategori',
            'Jumlah Buku',
        ];
    }

    public function collection(): Collection
    {
        return BookCategory::with('books')->get();
    }

    public function map($bookCategory): array
    {
        return [
            ++$this->no, //pre-incremment : nambahin sblm di munculin
            $bookCategory->name,
            $bookCategory->books->count(),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $totalRows = $sheet->getHighestRow(); // Mendapatkan jumlah baris tertinggi
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],

            //atur styling untuk a1:c10 (10 misal jumlah datanya 9, dinamis sesuati totalRows)
            'A1:C' . $totalRows => [
                'borders' => [
                    // atur border untuk semua ('allBorders'), jika mau diatas doang bisa ganti allBorders jadi top, left, right, bottom
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ],
        ];
    }
}