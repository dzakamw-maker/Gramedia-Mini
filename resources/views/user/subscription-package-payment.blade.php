@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-title text-center">
                        <h3>Scan QR code</h3>
                        <p class="text-secondary">Gunakan aplikasi e-wallet atau m-bankin Anda untuk melakukan pembayaran</p>
                    </div>

                    <div class="card-body d-flex justify-content-center">
                        {!! QrCode::size(250)->generate($subscriptionPackage->id); !!}
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <b>Total Item</b>
                            <b>1</b>
                        </div>
                        <div class="d-flex justify-content-between">
                            <b>harga</b>
                            @php
                                $priceBeforeDiscount = ($subscriptionPackage->price * 0.2) + $subscriptionPackage->price;
                            @endphp
                            <b>Rp {{ number_format($priceBeforeDiscount, 0, ',', '.') }}</b>
                        </div>
                        <div class="d-flex justify-content-between">
                            <b>Diskon</b>
                            <b class="text-danger">-Rp {{ number_format(($subscriptionPackage->price * 0.2), 0, ',', '.') }}</b>
                        </div>
                        <hr>
                        <div class="d-flex  justify-content-end">
                            <h3 class="text-primary"> Rp {{ number_format($subscriptionPackage->price, 0, ',', '.') }}</h3>
                        </div>
                        <form method="POST" action="{{ route('subscription-packages.payment.send', $subscriptionPackage->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100 rounded-pill mt-2"> Bayar Sekarang</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection