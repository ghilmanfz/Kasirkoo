@extends('layouts.master')

@section('title')
    Daftar Pembelian
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Pembelian</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm()" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Transaksi Baru</button>
                @empty(! session('id_pembelian'))
                @endempty
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-pembelian">
                    <thead>
                        <th width="5%">id pembelian</th>
                        <th>Tanggal</th>
                        <th>Supplier</th>
                        <th>Total Item</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Diskon</th>
                        <th>Total Bayar</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('pembelian.supplier')
@includeIf('pembelian.detail')
@endsection
@push('scripts')
<script>
let table, table1;

$(function () {
    table = $('.table-pembelian').DataTable({
        processing : true,
        autoWidth  : false,
        ajax       : '{{ route('pembelian.data') }}',
        columns    : [
            {data: 'id_pembelian'},
            {data: 'tanggal'},
            {data: 'supplier'},
            {data: 'total_item'},
            {data: 'total_harga'},
            {data: 'status_badge', searchable:false, sortable:false},
            {data: 'diskon'},
            {data: 'bayar'},
            {data: 'aksi', searchable:false, sortable:false},
        ]
    });

    $('.table-supplier').DataTable();

    table1 = $('.table-detail').DataTable({
        processing:true,
        bSort:false,
        dom:'Brt',
        columns:[
            {data:'DT_RowIndex', searchable:false, sortable:false},
            {data:'kode_produk'},
            {data:'nama_produk'},
            {data:'harga_beli'},
            {data:'jumlah'},
            {data:'subtotal'},
        ]
    });
});

function addForm() {
    $('#modal-supplier').modal('show');
}

function showDetail(url) {
    $('#modal-detail').modal('show');
    table1.ajax.url(url).load();
}

function deleteData(url) {
    if (confirm('Yakin ingin menghapus data terpilih?')) {
        $.post(url,{
            _token : $('[name=csrf-token]').attr('content'),
            _method: 'delete'
        })
        .done(() => table.ajax.reload())
        .fail(() => alert('Tidak dapat menghapus data'));
    }
}

function receiveData(url){
    if(!confirm('Konfirmasi: barang sudah datang?')) return;
    $.ajax({
        url  : url,
        type : 'PUT',            
        headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success(){ table.ajax.reload(); },
        error (xhr){
            console.error(xhr.responseText);
            alert('Gagal memproses: '+xhr.status);
        }
    });
}
</script>
@endpush
