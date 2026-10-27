<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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

                ->addColumn('action', function ($row) {

                    $btnEdit = '<a href="' . route('admin.books.edit', $row->id) . '" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>';
                    $btnDelete = '<form action="' . route('admin.books.destroy', $row->id) . '" method="POST" style="display: inline-block">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin ingin menghapus buku ini?\')"> Hapus</button>
                            </form>';

                    $btnDetail = '<button type="button" class="btn btn-info btn-sm btn-detail"
                                data-cover="' . e($row->cover ? asset($row->cover) : '') . '"
                                data-title="' . e($row->title) . '"
                                data-category="' . e($row->bookCategory->name ?? '-') . '"
                                data-price="Rp ' . number_format($row->price, 0, ',', '.') . '"
                                data-writer="' . e($row->writer ?? '-') . '"
                                data-publisher="' . e($row->publisher ?? '-') . '"
                                data-language="' . e($row->language ?? '-') . '"
                                data-release="' . e($row->release_date ? date('d M Y', strtotime($row->release_date)) : '-') . '"
                                data-pages="' . e($row->page_of_book ?? '-') . '"
                                data-description="' . e($row->description ?? '') . '">
                                <i class="bi bi-eye"></i> Detail
                            </button>';
                    return $btnDetail . ' ' . $btnEdit . ' ' . $btnDelete;
                })

                ->rawColumns(['cover', 'action'])
                ->make(true);
        }

        return view('admin.books.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bookCategories = BookCategory::all();
        return view('admin.books.create', compact('bookCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_category_id' => ['required', 'exists:book_categories,id'],
            'cover' => ['required', 'image', 'mimes:jpg,jpeg,png,svg,webp'],
            'title' => ['required', 'string'],
            'writer' => ['required', 'string'],
            'publisher' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'language' => ['required', 'string'],
            'release_date' => ['required', 'date'],
            'page_of_book' => ['required', 'numeric'],
            'description' => ['nullable']
        ]);

        if ($request->file('cover')) {
            $cover = $request->file('cover');
            $nameFile = time() . '_' . $cover->getClientOriginalName();
            Storage::disk('public')->putFileAs('covers', $cover, $nameFile);
            $validated['cover'] = Storage::url('covers/' . $nameFile);
        }

        Book::create($validated);
        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        $bookCategories = BookCategory::all();
        return view('admin.books.edit', compact('book', 'bookCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $validatedData = $request->validate([
            'cover' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Ubah menjadi nullable
            'title' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric'],
            'description' => ['nullable', 'string'],
            'language' => ['required', 'string', 'max:255'],
            'publisher' => ['required', 'string', 'max:255'],
            'writer' => ['required', 'string', 'max:255'],
            'release_date' => ['required', 'date'],
            'page_of_book' => ['required', 'integer'],
            'book_category_id' => ['required', 'exists:book_categories,id'],
        ]);

        // Handle file upload jika ada gambar sampul baru yang diunggah
        if ($request->hasFile('cover')) {
            // 1. Hapus cover lama dari storage jika ada
            if ($book->cover) {
                // Ambil nama file asli dari URL (contoh: "/storage/covers/123_file.jpg" -> "covers/123_file.jpg")
                $oldImagePath = str_replace('/storage/', '', $book->cover);

                if (Storage::disk('public')->exists($oldImagePath)) {
                    Storage::disk('public')->delete($oldImagePath);
                }
            }

            // 2. Simpan cover baru
            $coverImage = $request->file('cover');
            $coverImageName = time() . '_' . $coverImage->getClientOriginalName();

            Storage::disk('public')->putFileAs('covers', $coverImage, $coverImageName);

            // 3. Set path URL cover baru ke array data yang divalidasi
            $validatedData['cover'] = Storage::url('covers/' . $coverImageName);
        }

        // Update data buku
        $book->update($validatedData);

        return redirect()->route('admin.books.index')->with('success', 'Data buku berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        if ($book->cover) {
            $oldImagePath = str_replace('/storage/', '', $book->cover);

            if (Storage::disk('public')->exists($oldImagePath)) {
                Storage::disk('public')->delete($oldImagePath);
            }
        }

        $book->delete();
        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil dihapus.');
    }
}