@extends('layouts.app')

@section('content')
<div class="card w-75 d-block mx-auto mt-3">
    <div class="card-header">
        <h1>Tambah Buku</h1>
    </div>

    <!-- Memunculkan semua error sekaligus -->
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="post" action="{{ route('admin.books.store') }}" enctype="multipart/form-data" class="card-body">
        @csrf
        <div class="row">
            <div class="col-6">
                <label for="book_category_id" class="form-label">Kategori Buku</label>
                <select class="form-select" name="book_category_id" id="book_category_id">
                    <option selected hidden disabled>Pilih Kategori Buku</option>
                    @foreach ($bookCategories as $bookCategory)
                    <option value="{{ $bookCategory->id }}">{{ $bookCategory->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6">
                <label for="title" class="form-label">Judul Buku</label>
                <input type="text" class="form-control" name="title" id="title">
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-6">
                <label for="writer" class="form-label">Penulis</label>
                <input type="text" class="form-control" name="writer" id="writer">
            </div>
            <div class="col-6">
                <label for="publisher" class="form-label">Penerbit</label>
                <input type="text" class="form-control" name="publisher" id="publisher">
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-6">
                <label for="price" class="form-label">Harga</label>
                <input type="text" class="form-control" name="price" id="price">
            </div>
            <div class="col-6">
                <label for="language" class="form-label">Bahasa</label>
                <select class="form-select" name="language" id="language">
                    <option selected hidden disabled>Pilih Bahasa</option>
                    <option value="Indonesia">Indonesia</option>
                    <option value="English">English</option>
                    <option value="Japan">Japan</option>
                    <option value="Arabic">Arabic</option>
                </select>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-6">
                <label for="release_date" class="form-label">Tanggal Rilis</label>
                <input type="date" class="form-control" name="release_date" id="release_date">
            </div>
            <div class="col-6">
                <label for="page_of_book" class="form-label">Jumlah Halaman</label>
                <input type="number" class="form-control" name="page_of_book" id="page_of_book">
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12">
                <label for="cover" class="form-label">Sampul Buku</label>
                <input type="file" class="form-control" name="cover" id="cover">
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12">
                <label for="description" class="form-label">Deskripsi Buku</label>
                <div id="description" style="height: 150px;"></div>
                <input type="hidden" name="description" id="description-input">
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top: 15%;">Simpan Buku</button>
    </form>
</div>
@endsection

@push('scripts')
    <script>
        const quill = new Quill('#description', {
            theme: 'snow'
        });

        quill.on('text-change', function() {
            document.getElementById('description-input').value = quill.root.innerHTML;
        });
    </script>
@endpush