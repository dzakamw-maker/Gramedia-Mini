<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPackageBook;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\SubscriptionPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class SubscriptionPackageBookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $packageId)
    {
        if ($request->ajax()) {
            $books = Book::with('bookCategory');

            return DataTables::of($books)
                ->addIndexColumn()

                ->editColumn('cover', function ($row) {
                    if ($row->cover) {
                        return '<img src="' . asset($row->cover) .
                            '" alt="' . e($row->title) .
                            '" class="img-thumbnail" style="max-height: 60px;">';
                    }

                    return '<span class="badge bg-secondary">No Cover</span>';
                })

                ->editColumn('price', function ($row) {
                    return 'Rp ' . number_format($row->price, 0, ',', '.');
                })

                ->addColumn('category_name', function ($row) {
                    return $row->bookCategory
                        ? $row->bookCategory->name
                        : '-';
                })

                // untuk mengecek apakah buku sudah ditambahkan ke paket langganan yang lagi dipilih
                ->addColumn('checkBook', function ($row) use ($packageId) {
                    // ambil book_id dari package_book yang package_id nya sesuai yang lagi dipilih
                    $bookIds = SubscriptionPackageBook::where('subscription_package_id', $packageId)->pluck('book_id')->toArray();
                    // pluck : mengambil data hanya dari 1 attribute yang dipanggil. hasilnya di SubscriptionPackageBook ada package_id sama book_id, yang akan diambil datanya hanya book_id. Data disimpan ke array dengan toArray()

                    return in_array($row->id, $bookIds) ? true : false;
                    // jika $row->id (id buku) ada di array $bookIds (yang isinya daftar book_id dari db package_book), maka kembalikan nilai true, jika tidak ada kembalikan nilai false
                })
                ->rawColumns(['cover'])
                ->make(true);
        }

        $subscriptionPackage = SubscriptionPackage::find($packageId);
        return view('admin.subscription-package-books.index', compact('subscriptionPackage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $packageId)
    {
        $request->validate([
            //memastikan bookIds ada isinya
            'bookIds' => 'required'
        ]);

        foreach ($request->bookIds as $key => $value) {
            //membuat data subscription package book
            SubscriptionPackageBook::create([
                //membuat data book id
                'book_id' => $value,
                'subscription_package_id' => $packageId
            ]);
        }

        //kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->route('admin.subscription-package-books', ['packageId' => $packageId])->with('success', 'Buku berhasil ditambahkan ke paket subscription');

    }

    /**
     * Display the specified resource.
     */
    public function show(SubscriptionPackageBook $subscriptionPackageBook)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubscriptionPackageBook $subscriptionPackageBook)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubscriptionPackageBook $subscriptionPackageBook)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubscriptionPackageBook $subscriptionPackageBook)
    {
        //
    }
}
