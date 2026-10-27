@extends ('layouts.app')
@section('content')
<div class="card mt-5 w-50 d-block mx-auto">
    <div class="card-header">
        <h1>Edit Kategori Buku</h1>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.book-categories.update', $bookCategory->id) }}" method="POST">
            @csrf
            {{-- override method mengganti method="POST" menjadi PUT sesuai dengan HTTP method routenya --}}
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nama Kategori</label>
                {{-- old('name', $bookCategory->name): jika ada error validasi, tampilkan nilai lama/nilai sebelumnya pada inputan
                    ini, jika tidak ada, tampilkan nilai dari $bookCategory->name --}}
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old
                    ('name', $bookCategory->name) }}" required>
                @error('name')
                <div class="invalid-feedback"> {{ $message }} </div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection