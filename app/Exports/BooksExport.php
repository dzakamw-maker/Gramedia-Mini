<?php

namespace App\Exports;

use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BooksExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    private int $no = 0;

    /**
     * Define the Excel column headings.
     */
    public function headings(): array
    {
        return [
            'Nomor',
            'Alamat Gambar Sampul Buku',
            'Nama Kategori Buku',
            'Judul Buku',
            'Penulis',
            'Penerbit',
            'Tanggal Rilis',
            'Jumlah Halaman',
            'Bahasa',
            'Harga',
        ];
    }

    /**
     * Fetch all books along with their category relation.
     */
    public function collection(): Collection
    {
        return Book::with('bookCategory')->get();
    }

    /**
     * Map each book record into a formatted Excel row.
     */
    public function map($book): array
    {
        return [
            ++$this->no,
            $book->cover ? asset($book->cover) : '-',
            $book->bookCategory?->name ?? '-',
            $book->title,
            $book->writer,
            $book->publisher,
            $book->release_date ? Carbon::parse($book->release_date)->format('d-m-Y') : '-',
            $book->page_of_book,
            $book->language,
            'Rp ' . number_format((int) $book->price, 0, ',', '.'),
        ];
    }

    /**
     * Apply bold gray header styling and thin borders around the table.
     */
    public function styles(Worksheet $sheet): array
    {
        $totalRows = $sheet->getHighestRow();

        return [
            'A1:J1' => [
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
            'A1:J' . $totalRows => [
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
