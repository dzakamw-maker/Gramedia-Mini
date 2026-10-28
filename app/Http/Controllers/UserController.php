<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Book;
use App\Models\SubscriptionPackage;
use App\Models\BookCategory;

class UserController extends Controller
{
    public function index()
    {
        return view('login');
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
        $validateDate = $request->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'max:12']
        ],
        [
            'name.required' => 'Nama lengkap harus diisi',
            'name.min' => 'Nama lengkap harus diisi dengan minimal 3 huruf',
            'email.require' => 'Email harus diisi',
            'email.unique' => 'Email sudah terdaftar, gunakam email lainnya',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password harus diisi dengan minimal 8 karakter',
            'password.max' => 'Password harus diisi dengan maksimal 12 karakter'
        ]); 

        User::create([
            'name' => $validateDate['name'],
            'email' => $validateDate['email'],
            'password' => Hash::make($validateDate['password'])
        ]);

        return redirect()->route('login')->with('success', 'Berhasil membuat akun! Silakan Login.');
    }

    public function authentication(Request $request)
    {
        $validateData = $request->validate([
            'email' => ['required'],
            'password' => ['required']
        ]);

        if(Auth::attempt($validateData)){
            $request->session()->regenerate();
            if (Auth::user()->role == "admin") {
                return redirect()->route('admin.dashboard')->with('success', 'Anda telah login sebagai admin!');
            } else {
                return redirect()->route('home')->with('success', 'Anda telah login!');
            }
            
        } else {
            return redirect()->route('login')->with('error', 'Email dan password tidak sesuai. Silakan coba lagi!');
        }
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('home')->with('success', 'Anda sudah logout');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserController $subscriptionPackageUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserController $subscriptionPackageUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserController $subscriptionPackageUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserController $subscriptionPackageUser)
    {
        //
    }

    public function home()
    {
        $subscriptionPackages = SubscriptionPackage::orderBy('created_at', 'desc')->limit(3)->get();
        $latestBooks = Book::orderBy('created_at', 'desc')->limit(10)->get();
        $freeBooks = Book::where('price', 0)->orderBy('created_at', 'desc')->limit(6)->get();
        return view('home', compact('subscriptionPackages', 'latestBooks', 'freeBooks'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        // LIKE mencari data yang mirip kata yang di cari 
        //orwhere mencari data dari field lain
        $books = Book::where('title', 'like', '%' . $query . '%')
            ->orWhere('writer', 'like', '%' . $query . '%')
            ->get();

        return view('search_result', compact('books', 'query'));
    }

    public function categoryBooks(Request $request, $id)
    {
        $category = BookCategory::findOrFail($id);

        $query = $request->input('q', $request->input('search'));
        $sort = $request->input('sort');

        $books = Book::where('book_category_id', $id);

        // Filter pencarian berdasarkan judul buku atau penulis
        if ($query) {
            $books->where(function ($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                    ->orWhere('writer', 'like', '%' . $query . '%');
            });
        }

        // Pengurutan data buku berdasarkan opsi dropdown (opsional)
        if ($sort == 'cheapest' || $sort == 'price_asc' || $sort == 'termurah') {
            $books->orderBy('price', 'asc');
        } elseif ($sort == 'expensive' || $sort == 'price_desc' || $sort == 'termahal') {
            $books->orderBy('price', 'desc');
        } elseif ($sort == 'latest' || $sort == 'terbaru') {
            $books->orderBy('created_at', 'desc');
        } else {
            $books->orderBy('created_at', 'desc');
        }

        $books = $books->get();

        return view('category_books', compact('category', 'books', 'query', 'sort'));
    }
}