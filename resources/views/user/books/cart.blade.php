@extends('layouts.app')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <h2 class="fw-bold mb-3">Keranjang Belanja</h2>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"
                aria-label="close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row g-4">
            {{-- Kolom kiri: daftar buku --}}
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-body py-3">
                        {{-- Pilih semua --}}
                        <span class="form-check-label">
                            Pilih semua : <b><span id="cart-count">{{ $carts->count() }}</span> Buku terpilih</b>
                        </span>
                    </div>
                </div>

                @forelse($carts as $cart)

                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            {{-- Checkbox --}}
                            <input type="checkbox" class="form-check-input m-0 cart-check"
                                name="selected_carts[]" value="{{ $cart->id }}" data-price="{{ $cart->display_price }}" checked>

                            {{-- Cover --}}
                            <a href="{{ route('books.show', $cart->book->id) }}" class="flex-shrink-0">
                                <img src="{{ asset($cart->book->cover ?? '-') }}"
                                    alt="{{ $cart->book->title ?? '' }}"
                                    class="rounded border"
                                    style="width: 180px; height: 250px; object-fit: cover;">
                            </a>

                            {{-- Detail buku --}}
                            <div class="col">
                                <span class="badge bg-light text-dark border mb-1">
                                    <i class="fa-solid fa-mobile me-1"></i>PDF
                                </span>
                                <div>
                                    <a href="{{ route('books.show', $cart->book->id) }}"
                                        class="fw-bold text-dark text-decoration-none">
                                        {{ $cart->book->title ?? '' }}
                                    </a>
                                </div>
                                <div class="text-secondary small">{{ $cart->book->writer ?? '' }}</div>
                                <div class="small text-muted">
                                    Opsi: {{ $cart->subscriptionPackage->name_package ?? 'Satuan' }}
                                </div>
                                <div class="d-flex align-items-center pt-2 mt-auto">
                                    <h5 class="mb-0">
                                        @php
                                        $price = 0;
                                        if($cart->subscription_package_id == NULL) {
                                        $price = $cart->book->price ?? 0;
                                        } else {
                                        $price = $cart->subscriptionPackage->price ?? 0;
                                        }
                                        @endphp
                                        <span class="text-success fw-bold fs-5">Rp {{ number_format($cart->display_price, 0, ',', '.') }}</span>
                                    </h5>
                                </div>
                                <form action="{{ route('cart.destroy', $cart->id) }}"
                                    method="POST" onsubmit="return confirm('Hapus item ini dari keranjang?')">
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
                @empty
                <div class="card">
                    <div class="card-body text-center text-muted py-5">
                        Keranjang kamu masih kosong.
                    </div>
                </div>
                @endforelse
            </div>
            <!-- ringkasan harga -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">Ringkasan Keranjang</h5>
                        <div class="d-flex justify-content-between mb-3 text-muted">
                            <span>Total Belanja(
                                <span id="total-selected-count">{{ $carts->count() }}</span>
                                Buku)
                            </span>
                        </div>

                        <div class="mb-2">
                            @foreach($carts as $cart)
                            <div class="summary-row d-flex justify-content-between small mb-1 text-muted"
                                data-cart-id="{{ $cart->id }}">
                                <span class="text-truncate pe-3">
                                    {{ $cart->book->title }}
                                </span>
                                <span class="fw-medium" style="text-align: right;">
                                    Rp{{ number_format($cart->display_price, 0, ',', '.') }}
                                    @if($cart->needs_package)
                                    <br><small class="text-danger">Perlu membeli paket
                                        {{ $cart->subscriptionPackage->name_package }}
                                    </small>
                                    @endif
                                </span>
                            </div>
                            @endforeach
                        </div>

                        <hr class="my-3">

                        <div class="d-flex justify-content-between mb-3 text-muted">
                            <span>Total Belanja</span>
                            <h3 class="text-dark" id="total-price-text">Rp{{ number_format($totalPrice, 0, ',', '.' ) }}</h3>
                        </div>

                        <form method="POST"  action="{{ route('checkout.store') }}" id="form-checkout">
                            @csrf
                            <div id="checkout-inputs"></div>
                            <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-pill fw-semibold" title="Hapus" id="btn-checkout">
                                Checkout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const checkboxes = document.querySelectorAll('.cart-check');
        const totalPriceText = document.getElementById('total-price-text');
        const totalCountText = document.getElementById('total-selected-count');
        const cartCount = document.getElementById('cart-count');
        const btnCheckout = document.getElementById('btn-checkout');
        const checkoutInputs = document.getElementById('checkout-inputs');

        const rupiah = n => 'Rp ' + n.toLocaleString('id-ID');

        function updateSummary() {
            const checked = [...checkboxes].filter(cb => cb.checked);
            const total = checked.reduce((sum, cb) => sum + Number(cb.dataset.price), 0);

            totalPriceText.textContent = rupiah(total);
            totalCountText.textContent = checked.length;
            cartCount.textContent = checked.length;

            // tampilkan harga hanya untuk buku yang dicentang
            checkboxes.forEach(cb => {
                const row = document.querySelector(`.summary-row[data-cart-id="${cb.value}"]`);
                if (row) row.classList.toggle('d-none', !cb.checked);
            });

            btnCheckout.disabled = checked.length === 0;

            checkoutInputs.innerHTML = '';
            checked.forEach(cb => {
                checkoutInputs.innerHTML += `<input type="hidden" name="selected_carts[]" value="${cb.value}">`;
            });
        }

        checkboxes.forEach(cb => cb.addEventListener('change', updateSummary));
        updateSummary();
    });
</script>
@endpush