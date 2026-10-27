@extends('layouts.app')
@push('styles')
<style>
    .slick-prev,
    .slick-next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        font-size: 0;
        color: transparent;
        cursor: pointer;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
        display: flex !important;
        align-items: center;
        justify-content: center;
    }

    .slick-prev:hover,
    .slick-next:hover {
        background: #f8fafc;
        transform: translateY(-50%) scale(1.05);
    }

    .slick-prev { left: -15px; }
    .slick-next { right: -15px; }

    .slick-prev::before,
    .slick-next::before {
        content: '';
        display: inline-block;
        border: solid #475569;
        border-width: 0 2px 2px 0;
        padding: 4px;
    }

    .slick-prev::before {
        transform: rotate(135deg);
        margin-left: 4px;
    }

    .slick-next::before {
        transform: rotate(-45deg);
        margin-right: 4px;
    }

    .slick-dots {
        display: flex !important;
        justify-content: center;
        align-items: center;
        list-style: none;
        padding: 0;
        margin-top: 20px;
        gap: 8px;
    }

    .slick-dots li button {
        font-size: 0;
        color: transparent;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #cbd5e1;
        border: none;
        padding: 0;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .slick-dots li.slick-active button {
        background-color: #066fd1;
        width: 24px;
        border-radius: 10px;
    }

    /* Penyesuaian khusus agar card & gambar buku slider berukuran ideal */
    .book-card-img {
        height: 180px;
        object-fit: contain;
    }
</style>
@endpush

@section('content')
@if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

{{-- Carousel Banner --}}
<div id="carousel-sample" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carousel-sample" data-bs-slide-to="0" aria-label="Slide 1" aria-current="true" class="active"></button>
        <button type="button" data-bs-target="#carousel-sample" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carousel-sample" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img class="d-block w-100" alt src="https://i.pinimg.com/1200x/58/52/30/58523074e9d6b922d83e5a76de912392.jpg" />
        </div>
        <div class="carousel-item">
            <img class="d-block w-100" alt src="https://i.pinimg.com/1200x/58/52/30/58523074e9d6b922d83e5a76de912392.jpg" />
        </div>
        <div class="carousel-item">
            <img class="d-block w-100" alt src="https://i.pinimg.com/1200x/58/52/30/58523074e9d6b922d83e5a76de912392.jpg" />
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carousel-sample" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carousel-sample" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

{{-- Paket Langganan --}}
<div class="mt-4 p-5">
    <div class="d-flex align-items-center gap-2 mb-4">
        <span class="badge bg-yellow text-yellow-fg p-2">
            <i class="fa-solid fa-crown fs-3"></i>
            <h2 class="mt-3 text-dark">Paket Langganan</h2>
        </span>
    </div>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100" style="background: linear-gradient(135deg, #ffffff 0%, #a7f1c6 100%);">
                <div class="card-body row">
                    <div class="col-4"></div>
                    <div class="col-6 text-center text-dark">
                        <h2 style="font-weight: bold">NON FICTION</h2>
                        <p class="text-secondary" style="font-weight: bold; margin: 0 !important">Package</p>
                        <div>
                            Rp <span style="font-size: 2rem; font-weight: bold;" class="text-warning">49.000</span><br>
                            <span class="text-secondary" style="font-weight: bold; margin: 0 !important">/30 days</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100" style="background: linear-gradient(135deg, #ffffff 0%, #a49dd0 100%);">
                <div class="card-body row">
                    <div class="col-4"></div>
                    <div class="col-6 text-center text-dark">
                        <h2 style="font-weight: bold">FICTION</h2>
                        <p class="text-secondary" style="font-weight: bold; margin: 0 !important">Package</p>
                        <div>
                            Rp <span style="font-size: 2rem; font-weight: bold;" class="text-warning">79.000</span><br>
                            <span class="text-secondary" style="font-weight: bold; margin: 0 !important">/30 days</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f1b8a7 100%);">
                <div class="card-body row">
                    <div class="col-4"></div>
                    <div class="col-6 text-center text-dark">
                        <h2 style="font-weight: bold">PREMIUM</h2>
                        <p class="text-secondary" style="font-weight: bold; margin: 0 !important">Package</p>
                        <div>
                            Rp <span style="font-size: 2rem; font-weight: bold;" class="text-warning">109.000</span><br>
                            <span class="text-secondary" style="font-weight: bold; margin: 0 !important">/30 days</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Buku Terbaru --}}
<div class="mt-4">
    <div class="d-flex align-items-center gap-2 mb-4">
        <h2 class="mt-3 ms-5 text-dark" style="font-weight: bold;">Buku Baru Dirilis</h2>
    </div>
    <div id="wrapper-slider">
        @forelse ($latestBooks as $book)
        <div class="px-2">
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
        @empty
            <p class="text-center">Tidak ada buku terbaru.</p>
        @endforelse
    </div>
</div>

{{-- Buku Gratis --}}
<div class="mt-4">
    <div class="d-flex align-items-center gap-2 mb-4 mx-5">
        <h2 class="mt-3 text-dark" style="font-weight: bold;">Buku Gratis</h2>
    </div>

    <div class="row g-3">
        @forelse ($freeBooks as $book)
        <div class="col-md-4">
            <div class="card d-flex flex-column h-100">
                <div class="row row-0 flex-fill align-items-center">
                    <div class="col-md-4 p-2 text-center">
                        <a href="#">
                            <img src="{{ asset($book->cover) }}" 
                                 alt="Buku Gratis" class="img-fluid rounded" style="max-height: 120px; object-fit: contain;">
                        </a>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body p-3 d-flex flex-column h-100">
                            <div class="card-title mb-2">
                                <span class="badge bg-light text-dark border"><i class="fa-solid fa-mobile me-1"></i>PDF</span>
                            </div>
                            <div class="text-secondary" style="font-size: 0.85rem;">
                                {{ $book->writer }}
                                <br>
                                <strong class="text-dark" style="font-size: 0.95rem;">{{ $book->title }}</strong>
                            </div>
                            <div class="d-flex align-items-center pt-2 mt-auto">
                                <h5 class="mb-0">
                                    <span class="text-decoration-line-through text-secondary me-2 fs-6">Rp 50.000</span>
                                    <span class="text-success fw-bold fs-5">Rp 0</span>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
            <p class="text-center">Tidak ada buku gratis.</p>
        @endforelse
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#wrapper-slider').slick({
            dots: true,
            infinite: false,
            speed: 300,
            slidesToShow: 4,
            slidesToScroll: 4,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    });
</script>
@endpush