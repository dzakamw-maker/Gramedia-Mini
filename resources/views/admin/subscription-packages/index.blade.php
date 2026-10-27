@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Paket Langganan</h1>
        <a href="{{ route('admin.subscription-packages.create') }}" class="btn btn-primary mb-3">Tambah Paket</a>
    </div>
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-responsive bg-white w-100" id="subscription-packages-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Paket</th>
                        <th>Deskripsi</th>
                        <th>Warna</th>
                        <th>Harga</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Diisi otomatis oleh DataTables via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#subscription-packages-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.subscription-packages.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name_package',
                    name: 'name_package'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'color',
                    name: 'color'
                },
                {
                    data: 'price',
                    name: 'price',
                    render: function(data, type) {
                        return type === 'display'
                            ? 'Rp ' + new Intl.NumberFormat('id-ID').format(data)
                            : data;
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });
    });
</script>
@endpush