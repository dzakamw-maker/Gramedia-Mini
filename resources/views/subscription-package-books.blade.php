@extends('layouts.app')

@push('styles')
<style>
    .book-card-img {
        height: 180px;
        object-fit: contain;
    }
</style>
@endpush

@section('content')
<div class="container mt-4 mb-5">
    {{-- Header Paket & Tombol Langganan di Kanan --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 pb-3 border-bottom">
        <div>
            <h2 class="mb-1 fw-bold text-dark">Buku Paket {{ $subscriptionPackage->name_package }}</h2> 
            @if(!empty($subscriptionPackage->description))
                <p class="text-secondary small mb-2">{{ $subscriptionPackage->description }}</p>
            @endif
            <span class="badge bg-secondary-subtle text-secondary py-2 px-3">
                Rp {{ number_format($subscriptionPackage->price, 0, ',', '.') }} / 30 Days
            </span>
        </div>
        <div>
            <a href="{{ route('subscription-packages.payment', $subscriptionPackage->id) }}" class="btn btn-primary d-inline-flex align-items-center gap-2 px-3 py-2">
                <i class="fa-solid fa-crown"></i> Langganan Sekarang    
            </a>
        </div>
    </div>

    {{-- Baris Filter & Pencarian --}}
    <div class="d-flex justify-content-end mb-4">
        <form action="{{ route('package.books', $subscriptionPackage->id) }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap">
            <div class="input-icon">
                <input type="text" name="q" value="{{ $query }}" class="form-control" placeholder="cari judul atau penulis" style="min-width: 240px;">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M3 10a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                </span>
            </div>
            <select name="sort" class="form-select" onchange="this.form.submit()" style="min-width: 160px;">
                <option value="">Urutkan</option>
                <option value="cheapest" {{ $sort == 'cheapest' || $sort == 'termurah' ? 'selected' : '' }}>Harga termurah</option>
                <option value="expensive" {{ $sort == 'expensive' || $sort == 'termahal' ? 'selected' : '' }}>Harga termahal</option>
                <option value="latest" {{ $sort == 'latest' || $sort == 'terbaru' ? 'selected' : '' }}>Buku terbaru</option>
            </select>
            <button type="submit" class="btn btn-primary">Cari</button>
            @if($query || $sort)
                <a href="{{ route('package.books', $subscriptionPackage->id) }}" class="btn btn-outline-secondary">Reset</a>
            @endif
        </form>
    </div>

    @if($books->isEmpty())
        <div class="alert alert-info mt-3">
            @if($query)
                Tidak ada buku dalam paket langganan <b>{{ $subscriptionPackage->name_package }}</b> untuk kata kunci <b>"{{ $query }}"</b>.
            @else
                Tidak ada buku dalam paket langganan <b>{{ $subscriptionPackage->name_package }}</b>.
            @endif
        </div>
    @else
        <div class="row g-3">
            @foreach($books as $book)
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <div class="card h-100">
                    <div class="card-body p-3 d-flex flex-column">
                        <img src="{{ asset($book->cover) }}"
                            alt="Cover Buku" class="d-block mx-auto book-card-img mb-2">
                        <div class="d-flex gap-2 mt-2">
                            <span class="badge bg-secondary-subtle text-secondary"><i class="fa-solid fa-mobile me-1"></i>PDF</span>
                            <span class="badge bg-secondary-subtle text-secondary">17+</span>
                        </div>
                        <div class="mt-2 flex-grow-1">
                            <span style="font-size: 0.8rem;" class="text-secondary d-block text-truncate">{{ $book->writer }}</span>
                            <h5 class="mb-1 text-truncate" style="font-size: 0.95rem; font-weight: 600;" title="{{ $book->title }}">{{ $book->title }}</h5>
                        </div>
                        <div class="mt-auto pt-1">
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
