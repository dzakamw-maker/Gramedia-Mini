@extends('layouts.app')

@section('content')
<div class="container mt-4 mb-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-3">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible mb-3">
            {{ session('error') }}
        </div>
    @endif

    <div class="row g-4">
        {{-- Kolom Kiri: Profil & Ringkasan --}}
        <div class="col-md-4 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <span class="avatar avatar-md rounded-circle bg-secondary-subtle text-secondary fw-bold fs-3">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </span>
                        <div>
                            <h3 class="mb-0 fw-bold text-dark">{{ $user->name }}</h3>
                        </div>
                    </div>

                    <h4 class="fw-bold text-secondary mb-3">Profil Summary</h4>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary">Koleksi Buku</span>
                        <span class="fw-bold text-dark">{{ $bookCollectionCount }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary">Langganan</span>
                        <span class="fw-bold text-dark">{{ $subscriptionCount }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Riwayat Transaksi --}}
        <div class="col-md-8 col-lg-9">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                        <h3 class="card-title fw-bold fs-2 mb-0">Transaksi</h3>
                        <div>
                            <label class="form-label small text-secondary mb-1">Jenis Transaksi</label>
                            <form action="{{ route('profile') }}" method="GET" id="filterForm">
                                <select name="filter" class="form-select" onchange="this.form.submit()" style="min-width: 170px;">
                                    <option value="paket_langganan" {{ $filter == 'paket_langganan' ? 'selected' : '' }}>Paket Langganan</option>
                                    <option value="buku" {{ $filter == 'buku' ? 'selected' : '' }}>Buku</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    @if($filter == 'buku')
                        {{-- Daftar Transaksi Buku --}}
                        @forelse($checkouts as $checkout)
                            <div class="border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-secondary small">
                                        {{ $checkout->created_at ? $checkout->created_at->format('d M Y, H:i') : '-' }}
                                    </span>
                                    <span class="badge border border-success text-success bg-transparent px-2 py-1">
                                        {{ $checkout->status == 'Done' ? 'Transaksi Sukses' : $checkout->status }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div>
                                        <h4 class="mb-1 fw-bold text-dark">{{ $checkout->total_book }} Buku</h4>
                                        <span class="badge bg-secondary-subtle text-secondary">{{ $checkout->payment_method }}</span>
                                    </div>
                                    <div class="text-end">
                                        <span class="text-secondary small d-block">Total Pembayaran</span>
                                        <span class="fw-bold text-dark fs-3">
                                            Rp {{ number_format($checkout->total_price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-secondary py-5">
                                <p class="mb-0">Belum ada riwayat transaksi buku.</p>
                            </div>
                        @endforelse
                    @else
                        {{-- Daftar Transaksi Paket Langganan --}}
                        @forelse($subscriptions as $sub)
                            <div class="border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-secondary small">
                                        {{ $sub->created_at ? $sub->created_at->format('d M Y, H:i') : '-' }}
                                    </span>
                                    <span class="badge border border-success text-success bg-transparent px-2 py-1">Transaksi Sukses</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div class="d-flex align-items-center gap-3">
                                        <h4 class="mb-0 fw-bold text-dark">
                                            {{ $sub->subscriptionPackage?->name_package ?? 'Paket Langganan' }}
                                        </h4>
                                        <span class="badge bg-danger text-white rounded-pill px-3 py-1">
                                            Berlaku sd : {{ $sub->expired_date ? \Carbon\Carbon::parse($sub->expired_date)->format('d M Y') : '-' }}
                                        </span>
                                    </div>
                                    <div class="text-end">
                                        <span class="text-secondary small d-block">Total Pembayaran</span>
                                        <span class="fw-bold text-dark fs-3">
                                            Rp {{ number_format($sub->subscriptionPackage?->price ?? 0, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-secondary py-5">
                                <p class="mb-0">Belum ada riwayat transaksi paket langganan.</p>
                            </div>
                        @endforelse
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
