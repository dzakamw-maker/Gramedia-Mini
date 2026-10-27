@extends('layouts.app')
@section('content')
<div class="container mt-5">
<h1>Admin Dashboard</h1>
<p>Welcome, {{ Auth:: user()->name }}!</p>
</div>
@endsection