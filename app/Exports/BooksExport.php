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
    private int $no = 0; // Inisialisasi nomor urut mulai dari 0

    // Menentukan judul kolom (header) pada file Excel
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

    // Mengambil seluruh data buku beserta relasi kategori bukunya
    public function collection(): Collection
    {
        return Book::with('bookCategory')->get();
    }

    // Memetakan setiap data buku ke dalam baris Excel
    public function map($book): array
    {
        return [
            ++$this->no, // Nomor urut otomatis (pre-increment)
            $book->cover ? asset($book->cover) : '-', // Alamat URL gambar sampul buku
            $book->bookCategory?->name ?? '-', // Nama kategori buku dari relasi
            $book->title,
            $book->writer,
            $book->publisher,
            $book->release_date ? Carbon::parse($book->release_date)->format('d-m-Y') : '-', // Format tanggal menjadi tanggal-bulan-tahun
            $book->page_of_book,
            $book->language,
            'Rp ' . number_format((int) $book->price, 0, ',', '.'), // Format harga ke bentuk Rupiah
        ];
    }

    // Mengatur styling header tebal abu-abu dan border tabel
    public function styles(Worksheet $sheet): array
    {
        $totalRows = $sheet->getHighestRow(); // Mendapatkan jumlah baris tertinggi pada sheet

        return [
            // Membuat teks header tebal dan memberi warna latar abu-abu pada A1:J1
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
            // Memberikan border tipis di seluruh sel tabel dari A1 sampai kolom J baris terakhir
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
