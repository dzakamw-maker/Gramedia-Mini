@extends('layouts.app')

@section('content')
    <div class="container my-3">
        <div class="row">
            <h3>Keranjang Belanja</h3>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button class="brn-clos" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="col-8">


                <div class="card p-3">
                    <div>
                        <input type="checkbox" name="" id="select-all">
                        <label for="select-all" class="ms-2">Pilih semua : <b><span class="cart-count"
                                    id="cart-count">{{ $carts->count() }}</span> Buku terpilih</b> </label>
                    </div>
                </div>

                @foreach ($carts as $cart)
                    <div class="card mt-2 p-3 d-flex flex-row align-items-center">
                        <!-- Checkbox pilih buku -->
                        <div class="me-3">
                            <input type="checkbox" name="selected_carts[]" value="{{ $cart->id }}" class="cart-checkbox">
                        </div>

                        <!-- detail buku -->
                        <div class="card d-flex flex-column h-100 flex-grow-1">
                            <div class="row row-0 flex-fill align-items-center">
                                <div class="col-auto p-2">
                                    <a href="#" class="d-block">
                                        <div class="rounded bg-light border d-flex align-items-center justify-content-center overflow-hidden"
                                            style="width: 85px; height: 115px;">
                                            <img src="{{ asset($cart->book->cover ?? '-') }}" alt="Cover Buku"
                                                style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    </a>
                                </div>
                                <div class="col">
                                    <div class="card-body p-3 d-flex flex-column h-100">
                                        <div class="card-title mb-2">
                                            <span class="badge bg-light text-dark border"><i
                                                    class="fa-solid fa-mobile me-1"></i>PDF</span>
                                        </div>
                                        <div class="text-secondary" style="font-size: 0.85rem;">
                                            {{ $cart->book->writer ?? '-' }}
                                            <br>
                                            <strong class="text-dark"
                                                style="font-size: 0.95rem;">{{ $cart->book->title ?? '-' }}</strong>
                                        </div>
                                        <div class="d-flex align-items-center pt-2 mt-auto">
                                            <h5 class="mb-0 fw-bold">
                                                @php
                                                    $price = 0;
                                                    if ($cart->subscription_package_id == NULL) {
                                                        $price = $cart->book->price ?? 0;
                                                    } else {
                                                        $price = $cart->subscriptionPackage->price ?? 0;
                                                    }
                                                @endphp
                                                <span class="text-dark">Rp {{ number_format($price, 0, ',', '.') }}</span>
                                            </h5>
                                        </div>

                                        <!-- delete -->
                                        <form action="{{ route('cart.destroy', $cart->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus item ini dari keranjang?')">
                                            @csrf

                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm mt-2" title="Hapus">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Ringkasan Harga -->
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">Ringkasan Belanja</h5>
                        <div class="d-flex justify-content-between mb-3 text-muted">
                            <span>Total Belanja (<span id="total-selected-count">0</span> Buku)</span>
                            <span class="text-dark fw-medium" id="total-price-text">Rp0</span>
                        </div>

                        <hr class="my-3">

                        <div class="d-flex justify-content-between mb-3 text-muted">
                            <span>Total Belanja</span>
                            <span class="text-dark" id="discount-text">Rp0</span>
                        </div>

                        <form id="form-checkout" method="GET">
                            <div id="checkout-input"></div>
                            <button type="submit" class="btn btn-primary py-2.5 w-100 rounded-pill fw-semibold"
                                title="Hapus">
                                Checkout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection