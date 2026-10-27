@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Hasil Pencarian: "{{ $query}}"</h1>

    @if($books->isEmpty())
    <div class="alert alert-info mt-2">
        Tidak ada buku yang ditemukan untuk kata kunci <b>{{ $query}}</b>
    </div>
    @else
    <div class="row mt-2">
        @foreach($books as $book)
        <div class="px-2 col-2">
            <div class="card h-100">
                <div class="card-body p-3">
                    <img src="{{ asset($book->cover) }}"
                        alt="Cover Buku" class="d-block mx-auto book-card-img mb-2">
                    <div class="d-flex gap-2 mt-2">
                        <span class="badge bg-secondary-subtle text-secondary"><i class="fa-solid fa-mobile"></i></span>
                        <span class="badge bg-secondary-subtle text-secondary">3+</span>
                    </div>
                    <div class="mt-2">
                        <span style="font-size: 0.8rem;" class="text-secondary">{{ $book->author }}</span>
                        <h5 class="mb-1" style="font-size: 0.95rem; font-weight: 600;">{{ $book->title }}</h5>
                        <h6 style="font-size: 1rem; font-weight: bold;" class="text-primary mb-0">Rp {{ number_format($book->price, 0, ',', '.') }}</h6>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

@endsection