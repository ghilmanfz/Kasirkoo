@extends('layouts.master')

@section('title')
    Daftar Supplier
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Supplier</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm('{{ route('supplier.store') }}')" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Tambah</button>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered">
                    <thead>
                        <th width="5%">ID Supplier</th>
                        <th>Nama</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th width="15%">Aksi</i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('supplier.form')
@endsection

@push('scripts')
<script>
    let table;

    $(function () {
        table = $('.table').DataTable({
            processing: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('supplier.data') }}',
            },
            columns: [
                { data: 'id_supplier', name: 'id_supplier' }, 
                {data: 'nama'},
                {data: 'telepon'},
                {data: 'alamat'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $('#modal-form').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                    .done((response) => {
                        $('#modal-form').modal('hide');
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Tidak dapat menyimpan data',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        return;
                    });
            }
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Supplier');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=nama]').focus();
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Supplier');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=nama]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=nama]').val(response.nama);
                $('#modal-form [name=telepon]').val(response.telepon);
                $('#modal-form [name=alamat]').val(response.alamat);
            })
            .fail((errors) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Tidak dapat menampilkan data',
                    showConfirmButton: false,
                    timer: 3000
                });
                return;
            });
    }

    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload();
                })
                .fail((errors) => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Tidak dapat menghapus data',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    return;
                });
        }
    }
</script>
@endpush