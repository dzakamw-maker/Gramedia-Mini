<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SubscriptionPackageUser;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();

        $carts = Cart::with(['book', 'subscriptionPackage'])
            ->where('user_id', $userId)
            ->get();

        // paket langganan aktif milik pengguna
        $activePackageIds = SubscriptionPackageUser::where('user_id', $userId)
            ->whereDate('expired_date', '>', today())
            ->pluck('subscription_package_id');

        // paket di keranjang yang belum dibeli pengguna
        $packageUserNotBought = $carts
            ->whereNotNull('subscription_package_id') // jangan ambil yg subscription_package_id nya null (buku satuan)
            ->pluck('subscription_package_id')
            ->unique() // subscription_package_id diambil unik (tidak duplikat)
            ->diff($activePackageIds) // membandingkan subscription_package_id yg belum ada di $activePackageIds (paket yg uda dibeli)
            ->values(); // ambil ulang collection yg sudah disesuaikan (filter)

        // menambahkan attribute baru ke data $carts
        $carts->each(function ($cart) use ($packageUserNotBought) {
            // untuk menyesuaikan harga yg ditampilkan : dari paket langganan atau satuan buku
            $cart->display_price = $cart->subscriptionPackage ? $cart->subscriptionPackage->price : $cart->book->price;
            // contains() mengembalikan true/false apakah $cart->subscription_package_id item ini ada di $packageUserNotBought (paket yg belum dibeli pengguna)
            $cart->needs_package = $packageUserNotBought->contains($cart->subscription_package_id);
        });

        // total harga dari semua item di keranjang
        $totalPrice = $carts->sum('display_price');

        return view('user.books.cart', compact('carts', 'totalPrice'));
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
    public function store(Request $request)
    {
        
        if (!$request->purchase_option){
            return redirect()->back()->with('error', 'Pastikan untuk memilih jenis harga pembayaran');
        }
        
        if ($request->action == "add_to_cart"){
            Cart::updateOrCreate([
                'user_id' => Auth::user()->id,
                'book_id' => $request->book_id
            ], [
                'subscription_package_id' => $request->purchase_option == "none" ? null : $request->purchase_option
            ]);
            return redirect()->back()->with("success","Buku Berhasil Ditambhakna ke Keranjang");
        } else {
          dd($request->all());  
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Cart::where('id', $id)->where('user_id', Auth::id())->delete();
        return redirect()->route('cart')->with('success', 'Buku berhasil dihapus dari keranjang');
    }
}
