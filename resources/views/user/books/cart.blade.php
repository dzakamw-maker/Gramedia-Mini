@extends('layouts.app')

    @section('content')
    <div class="container my-3">
        <div class="row">
            <h3>Keranjang Belanja</h3>
               @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            <!-- daftar buku -->
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <input type="checkbox" name="" id="select-all" class="form-check-input">
                        <label for="select-all" class="ms-2"><b><span
                                    class="cart-count" id="cart-count">({{ $carts->count() }})</span> Buku Terpilih</b></label>
                    </div>
                </div>

                @foreach($carts as $cart)
                <div class="card mt-2">
                    <div class="card-body">
                        <div class="row align-items-center g-3">
                            <div class="col-auto">
                                <input type="checkbox" name="selected_carts[]" class="form-check-input item-checkbox" value="{{ $cart->id }}"
                                data-price="{{ $cart->display_price }}" checked>
                            </div>
                            <div class="col">
                                <div class="card d-flex flex-column h-100">
                                    <!-- detail buku -->
                                    <div class="row row-0 flex-fill">
                                        <div class="col-md-3">
                                            <a href="#">
                                                <img src="{{ asset($cart->book->cover ?? '') }}" class="img-fluid rounded" alt="Card side image"
                                                    style="width: 2500px;  object-fit: cover;">
                                            </a>
                                        </div>
                                        <div class="col">
                                            <div class="card-body h-full d-flex flex-column">
                                                <h3 class="card-title">
                                                    <div class="badge"><i class="fa-solid fa-mobile"></i>PDF</div>
                                                </h3>
                                                <div class="text-secondary">{{ $cart->book->writer ?? '-'}}<br> <span class="text-dark">{{ $cart->book->title ?? '-' }}</span></div>
                                                <div class="mt-auto pt-3">
                                                    <h4 class="fw-bold mb-0">Rp{{ number_format($cart->display_price, 0, ',', '.') }}</h4>
                                                </div>

                                                <form action="{{ route('cart.destroy', $cart->id) }}"
                                                    method="POST"
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
                        </div>


                    </div>
                    <div>



                    </div>
                </div>
                @endforeach
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">Ringkasan Keranjang</h5>
                        <div class="mb-3 text-muted">
                            <div class="d-flex justify-content-between mb-2 text-muted">
                                <span>Total Belanja (<span id="total-selected-count">{{ $carts->count() }}</span> Buku)</span>
                            </div>

                            {{-- daftar harga per item --}}
                            <div class="mb-2">
                                @foreach ($carts as $cart)
                                <div class="summary-row d-flex justify-content-between small mb-1 {{ $cart->needs_package ? 'text-danger' : 'text-muted' }}"
                                    data-cart-id="{{ $cart->id }}">
                                    <span class="text-truncate pe-3">{{ $cart->book->title }}</span>
                                    <span class="fw-medium" style="text-align: right;">
                                        Rp{{ number_format($cart->display_price, 0, ',', '.') }}
                                        @if ($cart->needs_package)
                                        <br><small class="text-danger">Perlu membeli paket {{ $cart->subscriptionPackage->name_package }}</small>
                                        @endif
                                    </span>
                                </div>
                                @endforeach
                            </div>

                            <hr class="my-3">

                            <div class="d-flex justify-content-between border-top pt-2 mt-2">
                                <span>Total</span>
                                <span class="text-dark fw-medium" id="total-price-text">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <form method="GET" id="form-checkout">
                            <div id="checkout-inputs"></div>
                            <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-pill fw-semibold" id="btn-checkout">
                                Checkout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const totalPriceText = document.getElementById('total-price-text');
        const totalCountText = document.getElementById('total-selected-count');
        const cartCount = document.getElementById('cart-count');
        const btnCheckout = document.getElementById('btn-checkout');
        const checkoutInputs = document.getElementById('checkout-inputs');

        // fungsi untuk format rupiah
        const rupiah = n => 'Rp ' + n.toLocaleString('id-ID');

        function updateSummary() {
            // ambil element checkboxes, filter (ambil) hanya yang checked (terceklis)
            const checked = [...checkboxes].filter(cb => cb.checked);
            // reduce : looping kemudian buat nilai baru yang akan disimpan ke variable pembungkus reduce (const total). mempunyai 2 parameter, sum (variable penyimpan data yg sedang diolah), cb (checkbox yang sedang dilooping)
            // dataset : mengambil value dari atribut element HTML yang diawali dengan "data-" (data-price)
            const total = checked.reduce((sum, cb) => sum + Number(cb.dataset.price), 0);

            // textContent : mengubah isi text dari element HTML
            totalPriceText.textContent = rupiah(total);
            totalCountText.textContent = checked.length;
            cartCount.textContent = `(${checked.length})`;

            checkboxes.forEach(cb => {
                // ambil class summary-row yang spesifik berdasarkan data-cart-id nya
                const row = document.querySelector(`.summary-row[data-cart-id="${cb.value}"]`);
                if (row) {
                    if (cb.checked) { // jika checkbox yg sedang di loop diceklis, hilangkan d-none
                        row.classList.remove('d-none');
                    } else { // jika tidak, munculkan d-none (class untuk menyembunyikan element HTML)
                        row.classList.add('d-none');
                    }
                }
            });

            if (checked.length === 0) {
                btnCheckout.disabled = true; // Matikan tombol jika tidak ada yang dicentang
            } else {
                btnCheckout.disabled = false; // Aktifkan tombol jika ada yang dicentang
            }

            // isi nilai default dari HTML yang akan disimpan di const checkoutInputs
            checkoutInputs.innerHTML = '';
            // looping checkbox yg di ceklis (const checked) untuk membuat input hidden yang akan dikirim ke controller saat btn checkout di klik. name dan value sama seperti checkbox
            checked.forEach(cb => {
                checkoutInputs.innerHTML += `<input type="hidden" name="selected_carts[]" value="${cb.value}">`;
            });
        }

        // event listener untuk setiap checkbox, jika ada yang berubah (diceklis atau tidak), jalankan fungsi updateSummary
        checkboxes.forEach(cb => cb.addEventListener('change', updateSummary));

        const selectAll = document.getElementById('select-all');
        if (selectAll) {
            selectAll.addEventListener('change', () => {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
                updateSummary();
            });
        }
    });
</script>
@endpush