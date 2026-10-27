@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('login.auth') }}" class="form-fieldset w-50 mx-auto mt-5">
    @if(session('success'))
    <div class = "alert alert-success">{{ session('success' ) }}</div>
    @endif
    @if(session('error'))
    <div class = "alert alert-danger">{{ session('error' ) }}</div>
    @endif
    @csrf
  <legend class="form-label">LOGIN</legend>
    <label class="form-label required" for="fieldset-email">Email</label>
    <input type="email" id="fieldset-email" name="email" class="form-control @error('email') is-invalid @enderror" autocomplete="email" required value="{{ old('email') }}"/>
    @error('email')
    <small class='text-danger'>{{ $message }}</small>
    @enderror
  </div>
  <div class="mb-3">
    <label class="form-label" for="fieldset-phone">Password</label>
    <input type="password" id="fieldset-phone" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="tel" />
    @error('password')
    <small class='text-danger'>{{ $message }}</small>
    @enderror
  </div>
  <button type ="submit" class="btn btn-primary w-100">Kirim</button>
</formm>
@endsection