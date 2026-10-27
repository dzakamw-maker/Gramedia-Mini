<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'payment_method', 'total_book', 'total_book_price', 'total_price', 'status'])]
class Checkout extends Model
{
    public function checkoutBooks(): HasMany
    {
        return $this->hasMany(CheckoutBook::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}