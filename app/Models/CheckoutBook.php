<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['checkout_id', 'book_id', 'total_book'])]
class CheckoutBook extends Model
{
    public function checkout(): BelongsTo
    {
        return $this->belongsTo(Checkout::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}