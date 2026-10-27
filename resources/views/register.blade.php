@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('register.store') }}" class="form-fieldset w-50 mx-auto mt-5">
    @csrf
  <legend class="form-label">REGISTER</legend>
  <div class="mb-3">
    <label class="form-label required" for="fieldset-name">Nama Lengkap</label>
    <input type="text" id="fieldset-name" name="name" class="form-control @error('name') is-invalid @enderror" autocomplete="name" required value="{{ old('name') }}"/>
    @error('name')
    <small class='text-danger'>{{ $message }}</small>
    @enderror
  </div>
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