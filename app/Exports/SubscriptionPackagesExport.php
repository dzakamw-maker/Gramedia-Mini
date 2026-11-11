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
    private int $no = 0; // Inisialisasi nomor urut mulai dari 0

    // Memulai tabel dari sel A2 agar baris 1 bisa digunakan untuk judul
    public function startCell(): string
    {
        return 'A2';
    }

    // Menentukan judul kolom (header) pada file Excel
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

    // Mengambil data paket langganan beserta relasi buku dan pelanggan aktif
    public function collection(): Collection
    {
        return SubscriptionPackage::with([
            'subscriptionPackageBooks',
            'subscriptionPackageUsers' => function ($query) {
                // Hanya menghitung pelanggan yang tanggal expired-nya belum melewati hari ini
                $query->whereDate('expired_date', '>=', now()->toDateString());
            },
        ])->get();
    }

    // Memetakan setiap data paket langganan ke dalam baris Excel
    public function map($subscriptionPackage): array
    {
        return [
            ++$this->no, // Nomor urut otomatis (pre-increment)
            $subscriptionPackage->name_package,
            'Rp ' . number_format((int) $subscriptionPackage->price, 0, ',', '.'), // Format harga ke bentuk Rupiah
            $subscriptionPackage->subscriptionPackageBooks->count(), // Menghitung jumlah buku yang tertaut pada paket
            $subscriptionPackage->subscriptionPackageUsers->count(), // Menghitung jumlah pelanggan aktif yang tertaut
        ];
    }

    // Mengatur judul (ukuran h2), header tebal abu-abu, dan border tabel
    public function styles(Worksheet $sheet): array
    {
        $totalRows = $sheet->getHighestRow(); // Mendapatkan jumlah baris tertinggi pada sheet

        // Menambahkan teks judul di A1, menggabungkan sel A1:E1, dan mengatur tinggi baris 1
        $sheet->setCellValue('A1', 'Data Paket Langganan dengan Buku dan Pengguna');
        $sheet->mergeCells('A1:E1');
        $sheet->getRowDimension(1)->setRowHeight(28);

        return [
            // Mengatur teks judul agar tebal, berukuran seperti tag h2 (18pt), dan rata tengah
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
            // Membuat teks header tebal dan memberi warna latar abu-abu pada A2:E2
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
            // Memberikan border tipis di seluruh sel tabel dari A2 sampai kolom E baris terakhir
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
