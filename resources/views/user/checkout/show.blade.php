@extends('layouts.app')

@section('content')
    <div class="container my-5">
        <div class="card w-50 mx-auto d-block" >
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <p class="text-secondary">No. Order : {{ $checkout->order_code }}</p>
                    <p class="text-secondary">{{ Auth::user()->name }}</p>
                </div>
                <hr>
                @foreach ($checkout->checkoutBooks as $checkoutBook)
                    <div class="d-flex justify-content-between">
                        <p>{{ $checkoutBook->book->title }}</p>
                        <p>Rp {{ number_format($checkoutBook->price, 0,',','.') }}</p>
                    </div>
                @endforeach
                <hr>
                <h3>Total Harga : Rp {{ number_format($checkout->total_price, 0,',','.') }}</h3>

                @if ($checkout->    status == 'pending')
                <button class="btn btn-primary" id="pay-button">Pay Now</button>
                @else
                Payment successful
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script>
        const payButton = document.querySelector('#pay-button');
        if (payButton) {
            payButton.addEventListener('click', function(e) {
                e.preventDefault();

                snap.pay('{{ $snapToken }}', {
                    onSuccess: function(result) {
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    },
                    onPending: function(result) {
                        console.log(result);
                    },
                    onError: function(result) {
                        console.log(result);
                    }
                });
            });
        }
    </script>
@endpush