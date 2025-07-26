@extends('layouts.master')

@section('title')
    Transaksi Pembelian
@endsection

@push('css')
<style>
    .tampil-bayar {
        font-size: 5em;
        text-align: center;
        height: 100px;
    }

    .tampil-terbilang {
        padding: 10px;
        background: #f0f0f0;
    }

    

    @media(max-width: 768px) {
        .tampil-bayar {
            font-size: 3em;
            height: 70px;
            padding-top: 5px;
        }
    }
</style>
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Transaksi Pembelian</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <table>
                    <tr>
                        <td>Supplier</td>
                        <td>: {{ $supplier->nama }}</td>
                    </tr>
                    <tr>
                        <td>Telepon</td>
                        <td>: {{ $supplier->telepon }}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>: {{ $supplier->alamat }}</td>
                    </tr>
                </table>
            </div>
            <div class="box-body">
                    
                <form class="form-produk">
                    @csrf
                    <div class="form-group row">
                        <label for="kode_produk" class="col-lg-2">Kode Produk</label>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <input type="hidden" name="id_pembelian" id="id_pembelian" value="{{ $id_pembelian }}">
                                <input type="hidden" name="id_produk" id="id_produk">
                                <input type="text" class="form-control" name="kode_produk" id="kode_produk">
                                <span class="input-group-btn">
                                    <button onclick="tampilProduk()" class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                </form>


                <table class="table table-stiped table-bordered table-pembelian">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th width="15%">harga</th>
                        <th width="15%">Jumlah</th>
                        <th>Subtotal</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="tampil-bayar bg-primary"></div>
                        <div class="tampil-terbilang"></div>
                    </div>
                    <div class="col-lg-4">
                        <form action="{{ route('pembelian.store') }}" class="form-pembelian" method="post">
                            @csrf
                            <input type="hidden" name="id_pembelian" value="{{ $id_pembelian }}">
                            <input type="hidden" name="total" id="total">
                            <input type="hidden" name="total_item" id="total_item">
                            <input type="hidden" name="bayar" id="bayar">

                            <div class="form-group row">
                                <label for="totalrp" class="col-lg-2 control-label">Total</label>
                                <div class="col-lg-8">
                                    <input type="text" id="totalrp" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="diskon" class="col-lg-2 control-label">Diskon</label>
                                <div class="col-lg-8">
                                    <input type="number" name="diskon" id="diskon" class="form-control" value="{{ $diskon }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="bayar" class="col-lg-2 control-label">Bayar</label>
                                <div class="col-lg-8">
                                    <input type="text" id="bayarrp" class="form-control">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-sm btn-flat pull-right btn-simpan"><i class="fa fa-floppy-o"></i> Simpan Transaksi</button>
            </div>
        </div>
    </div>
</div>

@includeIf('pembelian_detail.produk')
@endsection

@push('scripts')
<script>
    let table, table2;

    $(function () {
        $('body').addClass('sidebar-collapse');

        table = $('.table-pembelian').DataTable({
    processing : true,
    serverSide : true,
    paging     : false,
    autoWidth  : false,
    ajax       : '{{ route('pembelian_detail.data', $id_pembelian) }}',
    columns    : [
        {data:'DT_RowIndex', orderable:false, searchable:false},
        {data:'kode_produk'},
        {data:'nama_produk'},
        {data:'harga_beli'},      
        {data:'jumlah'},
        {data:'subtotal'},
        {data:'aksi', orderable:false, searchable:false},
    ],
    dom : 'Brt',
    bSort : false,
        })
        .on('draw.dt', function () {
            loadForm($('#diskon').val());
        });
        table2 = $('.table-produk').DataTable();

        $(document).on('input', '.quantity', function () {
            let id = $(this).data('id');
            let jumlah = parseInt($(this).val());

            if (jumlah < 1) {
                $(this).val(1);
                alert('Jumlah tidak boleh kurang dari 1');
                return;
            }
            if (jumlah > 10000) {
                $(this).val(10000);
                alert('Jumlah tidak boleh lebih dari 10000');
                return;
            }

            $.post(`{{ url('/pembelian_detail') }}/${id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'put',
                    'jumlah': jumlah
                })
                .done(response => {
                    $(this).on('mouseout', function () {
                        table.ajax.reload(() => loadForm($('#diskon').val()));
                    });
                })
                .fail(errors => {
                    alert('Tidak dapat menyimpan data');
                    return;
                });
        });
$(document).on('input', '.harga-beli', function () {
    let id    = $(this).data('id');
    let harga = parseInt($(this).val());

    if (harga < 1) {
        $(this).val(1);
        alert('Harga tidak boleh 0');
        return;
    }

    $.post(`{{ url('/pembelian_detail') }}/${id}`, {
        _token : $('[name=csrf-token]').attr('content'),
        _method: 'put',
        harga_beli : harga      //  kirim harga
    })
    .done(() => {
        table.ajax.reload(() => loadForm($('#diskon').val()));
    })
    .fail(() => alert('Tidak dapat menyimpan harga'));
});
        $(document).on('input', '#diskon', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }

            loadForm($(this).val());
        });

        $('.btn-simpan').on('click', function () {
            $('.form-pembelian').submit();
        });
    });
    let timer;
$('#kode_produk').on('input', function () {
    clearTimeout(timer);

    const val = $(this).val().replace(/[\r\n\t ]+/g,''); // buang kontrol & spasi
    if (!val) return;

    timer = setTimeout(() => tambahProdukByKode(val), 250);
});

function tambahProdukByKode(kode) {
  $.get("{{ route('produk.getKode') }}", { kode_produk : kode })
    .done(res => {
        if (!res.success) {
            alert('Produk tidak ditemukan!');
            $('#kode_produk').select();
            return;
        }

        $('#id_produk').val(res.data.id_produk);
        $.post("{{ route('pembelian_detail.store') }}", $('.form-produk').serialize())
          .done(() => {
              table.ajax.reload(() => loadForm($('#diskon').val()));
              $('#kode_produk').val('').focus();
          });
    });
}


    function tampilProduk() {
        $('#modal-produk').modal('show');
    }

    function hideProduk() {
        $('#modal-produk').modal('hide');
    }

    function pilihProduk(id, kode) {
        $('#id_produk').val(id);
        $('#kode_produk').val(kode);
        hideProduk();
        tambahProduk();
    }

    function tambahProduk() {
        $.post('{{ route('pembelian_detail.store') }}', $('.form-produk').serialize())
            .done(response => {
                $('#kode_produk').focus();
                table.ajax.reload(() => loadForm($('#diskon').val()));
            })
            .fail(errors => {
                alert('Tidak dapat menyimpan data');
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
                    table.ajax.reload(() => loadForm($('#diskon').val()));
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }

   function loadForm(diskon = 0) {
    // 1) Hitung total (subtotal) dari kolom ke-6 (index 5)
    let total = table
      .column(5, { search: 'applied' })  // kolom subtotal
      .data()
      .reduce((sum, val) => {
        // val berupa string "Rp. 1.234", kita ambil hanya digitnya
        const num = parseInt(val.replace(/[^0-9]/g, ''), 10) || 0;
        return sum + num;
      }, 0);

    // 2) Hitung total_item dari semua input.quantity
    let totalItem = 0;
    $('.quantity').each(function() {
      totalItem += parseInt($(this).val(), 10) || 0;
    });

    // 3) Set ke hidden field (jika masih dipakai di form store)
    $('#total').val(total);
    $('#total_item').val(totalItem);

    // 4) Panggil endpoint dengan nilai diskon dan total yang benar
    $.get(`{{ url('/pembelian_detail/loadform') }}/${diskon}/${total}`)
      .done(response => {
        $('#totalrp').val('Rp. ' + response.totalrp);
        $('#bayarrp').val('Rp. ' + response.bayarrp);
        $('#bayar').val(response.bayar);
        $('.tampil-bayar').text('Rp. ' + response.bayarrp);
        $('.tampil-terbilang').text(response.terbilang);
      })
      .fail(errors => {
        console.error(errors.responseText);
        alert('Tidak dapat menampilkan data');
      });
}

</script>
@endpush