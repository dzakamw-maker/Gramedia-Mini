<?php

use App\Http\Controllers\UserController;
use Illuminate\Routing\RouteUri;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\BookCategoryController;
use App\Http\Controllers\SubscriptionPackageController;
use App\Http\Controllers\SubscriptionPackageBookController;
use App\Http\Controllers\BookController;

Route::get('/', [UserController::class, 'home'])->name('home');
Route::get('/search', [UserController::class, 'search'])->name('search');
Route::get('/category/{id}', [UserController::class, 'categoryBooks'])->name('category.books');

Route::middleware('IsGuest')->group(function () {
    Route::get('/register', function () {
        return view('register');
    })->name('register');
    Route::post('/register', [UserController::class, 'store'])->name('register.store');
    Route::get('login', [UserController::class, 'index'])->name('login');
    Route::post('/login', [UserController::class, 'authentication'])->name('login.auth');
});

Route::middleware(['IsLoggedIn'])->group(function () {
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');

    // kelompok route untuk admin yg pathnya diawali dengan /admin
    // seluruh route pada kelompok ini akan dipanggil dengan nama yang diawali dengan admin. (misal admin.dashboard)
    Route::prefix('admin')->name('admin.')->middleware('IsAdmin')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::resource('book-categories', BookCategoryController::class);
        Route::resource('subscription-packages', SubscriptionPackageController::class);

        Route::get('subscription-package-books/{packageId}', [SubscriptionPackageBookController::class,'index'])->name('subscription-package-books');
        Route::post('subscription-package-books/{packageId}', [SubscriptionPackageBookController::class,'store'])->name('subscription-package-books.store');

        Route::resource('books', BookController::class);
    });
});

// Route::get('/admin/dashboard', function() {
//     return view('admin.dashboard');
// })->middleware(IsAdmin::class);


/**Untuk route yg hanya ingin menampilakn view tanpa logic
 Route::get('path', function () {
     return view('nama_file');
 });
 */

/**
 *Untuk route yg  jika ada logic/butuh data dari data base
 *Route::get('path',[nama_conroller::class. 'namafunc] {
 *   return view('login');
 *});
 */

//method name() : berfungsi untuk memberikan route nama unik 
// aturan pemberian nama route : 
// 1. (/) diganti menjadi (.) contoh path = (/school/grades) nama_path (school.grade)
// 2. (-) diganti menjadi (_) contoh path = (/school/subject-theachers) nama_path (school.subject_teaher)
