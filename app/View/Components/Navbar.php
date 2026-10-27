<?php

namespace App\View\Components;

use App\Models\Book;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;  
use App\Models\BookCategory;

class Navbar extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $categories = BookCategory::orderBy('name', 'asc')->get();
        return view('components.navbar', [
            'navbarCategories' => $categories
        ]);
    }
}
