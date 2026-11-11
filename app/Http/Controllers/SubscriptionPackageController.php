<?php

namespace App\Http\Controllers;

use App\Exports\SubscriptionPackagesExport;
use App\Models\SubscriptionPackage;
use App\Models\SubscriptionPackageUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class SubscriptionPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // public function index()
    // {
    //     $subscriptionPackages = SubscriptionPackage::all();

    //     return view('admin.subscription-packages.index', compact('subscriptionPackages'));
    // }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $model = SubscriptionPackage::query();

            return DataTables::eloquent($model)
                // Menambahkan penomoran otomatis (kolom 'DT_RowIndex')
                ->addIndexColumn()
                // Menambahkan kolom tombol Aksi (Edit & Hapus)
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.subscription-packages.edit', $row->id);
                    $destroyUrl = route('admin.subscription-packages.destroy', $row->id);
                    $csrf = csrf_field();
                    $method = method_field('DELETE');

                    return '
                        <a href="' . $editUrl . '" class="btn btn-sm btn-warning ms-3">Edit</a>
                        <form action="' . $destroyUrl . '" method="POST" class="d-inline">
                            ' . $csrf . '
                            ' . $method . '
                            <button type="submit" class="btn btn-sm btn-danger ms-3" onclick="return confirm(\'Apakah Anda yakin ingin menghapus paket ini?\')">Hapus</button>
                        </form>'.
                        '<a class = "btn btn-sm btn-success ms-3" href="' . route('admin.subscription-package-books', ['packageId' => $row->id]) . '">Buku</a>'
                        ;
                })
                // Memberitahu Yajra bahwa kolom 'action' mengandung tag HTML
                ->rawColumns(['action'])
                ->toJson();
        }
    
        return view('admin.subscription-packages.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.subscription-packages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name_package' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'color' => ['required', 'string', 'max:255'],
            'price' => ['required', 'integer', 'min:0'],
        ]);

        SubscriptionPackage::create($validatedData);

        return redirect()->route('admin.subscription-packages.index')->with('success', 'Paket Langganan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SubscriptionPackage $subscriptionPackage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $subscriptionPackage = SubscriptionPackage::findOrFail($id);
        return view('admin.subscription-packages.edit', compact('subscriptionPackage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $subscriptionPackage = SubscriptionPackage::findOrFail($id);

        $validatedData = $request->validate([
            'name_package' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'color' => ['required', 'string', 'max:255'],
            'price' => ['required', 'integer', 'min:0'],
        ]);

        $subscriptionPackage->update($validatedData);

        return redirect()->route('admin.subscription-packages.index')->with('success', 'Paket Langganan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $subscriptionPackage = SubscriptionPackage::findOrFail($id);

        $subscriptionPackage->delete();

        return redirect()->route('admin.subscription-packages.index')->with('success', 'Paket Langganan berhasil dihapus.');
    }

    public function payment($id){
        $subscriptionPackage = SubscriptionPackage::findOrFail($id);
        return view('user.subscription-package-payment', compact('subscriptionPackage'));
    }

    public function paymentSend(Request $request, $id){
        SubscriptionPackageUser::create([
            'subscription_package_id'=> $id,
            'user_id'=> Auth::id(),
            'expired_date' => now()->addDays(30)->format('Y-m-d')
        ]);

        return redirect()->route('home')->with('success', 'Langganan berhasil diaktifkan. Paket berlaku hingga 30 hari ke depan.');

    }

    
    // Mengunduh data paket langganan dalam format file Excel
    public function export()
    {
        return Excel::download(new SubscriptionPackagesExport, 'paket-langganan.xlsx');
    }
}
