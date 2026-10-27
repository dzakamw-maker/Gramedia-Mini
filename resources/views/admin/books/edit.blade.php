@extends('layouts.app')

@section('content')
    <div class="card w-50 w-md-100-m mx-auto">
        <div class="card-header">
            <h5>Edit Buku</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="mb-3 col-6">
                        <label for="book_category_id" class="form-label">Kategori Buku</label>
                        <select class="form-select @error('book_category_id') is-invalid @enderror" id="book_category_id"
                            name="book_category_id">
                            <option value="">Pilih Kategori</option>
                            @foreach ($bookCategories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('book_category_id', $book->book_category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('book_category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 col-6">
                        <label for="title" class="form-label">Judul Buku</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                            name="title" value="{{ old('title', $book->title) }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="mb-3 col-6">
                        <label for="writer" class="form-label">Penulis</label>
                        <input type="text" class="form-control @error('writer') is-invalid @enderror" id="writer"
                            name="writer" value="{{ old('writer', $book->writer) }}">
                        @error('writer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 col-6">
                        <label for="publisher" class="form-label">Penerbit</label>
                        <input type="text" class="form-control @error('publisher') is-invalid @enderror" id="publisher"
                            name="publisher" value="{{ old('publisher', $book->publisher) }}">
                        @error('publisher')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="mb-3 col-6">
                        <label for="price" class="form-label">Harga</label>
                        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price"
                            name="price" value="{{ old('price', $book->price) }}">
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 col-6">
                        <label for="language" class="form-label">Bahasa</label>
                        @php
                            $language = ['Indonesia', 'English', 'Japan', 'Arabic'];
                        @endphp
                        <select class="form-select @error('language') is-invalid @enderror" id="language" name="language">
                            <option value="">Pilih Bahasa</option>
                            @foreach ($language as $lang)
                                <option value="{{ $lang }}"
                                    {{ old('language', $book->language) == $lang ? 'selected' : '' }}>
                                    {{ $lang }}
                                </option>
                            @endforeach
                        </select>
                        @error('language')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="mb-3 col-6">
                        <label for="release_date" class="form-label">Tanggal Rilis</label>
                        <input type="date" class="form-control @error('release_date') is-invalid @enderror"
                            id="release_date" name="release_date" value="{{ old('release_date', $book->release_date) }}">
                        @error('release_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 col-6">
                        <label for="page_of_book" class="form-label">Jumlah Halaman</label>
                        <input type="number" class="form-control @error('page_of_book') is-invalid @enderror"
                            id="page_of_book" name="page_of_book" value="{{ old('page_of_book', $book->page_of_book) }}">
                        @error('page_of_book')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="cover" class="form-label">Sampul Buku</label>

                    {{-- Tampilkan Preview Sampul Lama --}}
                    @if ($book->cover)
                        <div class="mb-2">
                            <img src="{{ asset($book->cover) }}" alt="Cover {{ $book->title }}"
                                class="img-thumbnail" style="max-height: 150px;">
                            <small class="text-muted d-block mt-1">Biarkan kosong jika tidak ingin mengubah sampul.</small>
                        </div>
                    @endif

                    <input type="file" class="form-control @error('cover') is-invalid @enderror" id="cover"
                        name="cover">
                    @error('cover')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="description" class="form-label">Deskripsi</label>
                    <div id="description"></div>

                    {{-- Menampung nilai awal dari database atau inputan sebelumnya --}}
                    <input type="hidden" name="description" id="description-input"
                        value="{{ old('description', $book->description) }}">
                    @error('description')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Perbarui Buku</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Library Texteditor QuillJS (Letakkan JS-nya) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quill/2.0.2/quill.min.js"></script>

    <script>
        const quill = new Quill('#description', {
            theme: 'snow',
        });

        const descriptionInput = document.getElementById('description-input');

        // Memuat nilai lama/saat ini ke dalam area pengeditan QuillJS
        if (descriptionInput.value) {
            quill.clipboard.dangerouslyPasteHTML(descriptionInput.value);
        }

        // Tangkap nilai tiap kali ada perubahan teks di Quill
        quill.on('text-change', function() {
            descriptionInput.value = quill.root.innerHTML;
        });
    </script>
@endpush