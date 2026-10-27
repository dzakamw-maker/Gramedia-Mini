<?php

namespace App\Http\Controllers;

use App\Models\BookCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BookCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $model = BookCategory::query();

            return DataTables::eloquent($model)
                // Menambahkan penomoran otomatis (kolom 'DT_RowIndex")
                ->addIndexColumn()
                // Menambahkan kolom tombol Aksi (Edit & Hapus)
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.book-categories.edit', $row->id);
                    $destroyUrl = route('admin.book-categories.destroy', $row->id);
                    $csrf = csrf_field();
                    $method = method_field('DELETE');

                    return '
                        <a href="' . $editUrl . ' class="btn btn-sm btn-warning">Edit</a>
                        <form action="' . $destroyUrl . '" method="POST" class="d-inline">
                            ' . $csrf . '
                            ' . $method . '
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Apakah Anda yakin ingin menghapus kategori ini?\')">Hapus</button>
                        </form>
                    ';
                })
                // Memberitahu Yajra bahwa kolom 'action' mengandung tag HTML
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('admin.book-categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.book-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        BookCategory::create($validatedData);

        return redirect()->route('admin.book-categories.index')->with('success', 'Kategori buku berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BookCategory $bookCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // findOrFail mencari data berdasarkan id, jika tidak ditemukan maka akan menampilkan error 404
        // atau bisa gunakan :
        // find mencari data berdasarkan id, jika tidak ditemukan maka akan mengembalikan null

        $bookCategory = BookCategory::findOrFail($id);
        return view('admin.book-categories.edit', compact('bookCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // jika data id tidak ditemukan maka akan menampilkan error 404, hentikan proses dibawahnya
        $bookCategory = BookCategory::findOrFail($id);

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max: 255'],
        ]);

        $bookCategory->update($validatedData);

        return redirect()->route('admin.book-categories.index')->with('success', 'Kategori buku berhasil
        diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // jika data id tidak ditemukan maka akan menampilkan error 404, hentikan proses dibawahnya
        $bookCategory = BookCategory::findOrFail($id);

        $bookCategory->delete();

        return redirect()->route('admin.book-categories.index')->with('success', 'Kategori buku berhasil
        dihapus.');
    }
}
