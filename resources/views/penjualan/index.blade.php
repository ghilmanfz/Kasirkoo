@extends('layouts.master')

@section('title')
    Daftar Penjualan
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Penjualan</li>
@endsection

@section('content')
<div class="box-header">
  <input type="date" id="export_date" value="{{ date('Y-m-d') }}">
  <button id="btn-export-date" class="btn btn-sm btn-primary">
    <i class="fa fa-file-pdf-o"></i> export pdf
  </button>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-penjualan">
                    <thead>
                        <th width="5%">id Transaksi</th>
                        <th>Tanggal</th>
                        <th>jam</th>
                        <th>Kode Member</th>
                        <th>Total Item</th>
                        <th>Total Harga</th>
                        <th>Diskon</th>
                        <th>Total Bayar</th>
                        <th>Kasir</th>
                        <th>metode Pembayran </th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    
</div>

@includeIf('penjualan.detail')
@endsection

@push('scripts')
<script>
    let table, table1;

    $(function () {
        table = $('.table-penjualan').DataTable({
            processing: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('penjualan.data') }}',
            },
             order      : [[0, 'desc']],
          columns: [
                {data: 'id_penjualan', searchable: false, sortable: false},
                {data:'tanggal'}, 
                {data:'jam'},
                {data: 'kode_member'},
                {data: 'total_item'},
                {data: 'total_harga'},
                {data: 'diskon'},
                {data: 'bayar'},
                {data: 'kasir'},
                {data: 'metode_pembayaran'}, // Tambahkan kolom ini
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });



        table1 = $('.table-detail').DataTable({
            processing: true,
            bSort: false,
            dom: 'Brt',
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_produk'},
                {data: 'nama_produk'},
                {data: 'harga_jual'},
                {data: 'jumlah'},
                {data: 'subtotal'},
            ]
        })
    });

    function showDetail(url) {
        $('#modal-detail').modal('show');

        table1.ajax.url(url);
        table1.ajax.reload();
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
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }
function printNota(url) {
  window.open(url, '_blank');
}

$('#btn-export-date').on('click', function() {
    const tgl = $('#export_date').val(); 
    if (!tgl) {
        return alert('Silakan pilih tanggal dulu.');
    }
    const url = '{{ url("penjualan/export") }}/' + tgl;
    window.open(url, '_blank', 'noopener');
});


</script>
@endpush