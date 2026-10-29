@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <form action="{{ route('admin.subscription-package-books.store', $subscriptionPackage->id) }}" method="POST">
            @csrf
        <div class="d-flex justify-content-between">
            <h1>Data Buku yang akan ditambahkan ke paket {{ $subscriptionPackage->name_package }}</h1>
            <button type="submit" class="d-none btn btn-primary" id="btnBuku"><span id="jmlhBuku">(0) </span> Tambah Buku</button>
        </div>

        <table class="table table-bordered" id="books-table">
            <thead>
                <tr>
                    <th>Pilih Buku</th>
                    <th></th>
                    <th>Sampul Buku</th>
                    <th>Judul</th>
                    <th>Kategori Buku</th>
                    <th>Penulis</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        </form>
    </div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@push('scripts')
    <script>
        $(document).ready(function () {
            const table = $('#books-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.subscription-package-books', ['packageId' => $subscriptionPackage->id]) }}",
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        render: function(id){
                            return `<input type="checkbox" value="${id}" class="inputBookId" name="bookIds[]" >`;
                        }

                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'cover',
                        name: 'cover',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'category_name',
                        name: 'category_name'
                    },
                    {
                        data: 'writer',
                        name: 'writer'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    }
                ]
            });

            //membuat function untuk update jumlah buku yang dipilih
            function updateSelectedBook(){
                //ambil jumlah buku yang dipilih
                let selectedCount = $('.inputBookId:checked').length;
                $('#jmlhBuku').text(selectedCount);

                //jika jumlah buku yang dipilih lebih besar dari 0 maka tombol tambah buku akan ditampilkan
                if(selectedCount > 0){
                    $('#btnBuku').removeClass('d-none');
                }else{
                    //jika jumlah buku yang dipilih lebih kecil dari 0 maka tombol tambah buku akan disembunyikan
                    $('#btnBuku').addClass('d-none');
                }
            }

            //ketika ada checkbox yang diubah maka akan memanggil function updateSelectedBook
            $('#books-table tbody').on('change', '.inputBookId', function () {
                updateSelectedBook();
            });
        });
    </script>
@endpush