<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name'])]
class BookCategory extends Model
{
    // nama jamak menggunakan s/es/ies karena book_categories berperan sebagai many pada relasi one to many milik kategori buku dan buku
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}