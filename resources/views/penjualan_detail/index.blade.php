@extends('layouts.master')

@section('title')
    Transaksi Penjualan
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

    .table-penjualan tbody tr:last-child {
        display: none;
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
    <li class="active">Transaksi Penjaualn</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body">
                    @csrf
                   <form class="form-produk">
    @csrf
    <div class="form-group row">
        <label for="kode_produk" class="col-lg-2">Kode Produk</label>
        <div class="col-lg-5">
            <div class="input-group">
                <input type="hidden" name="id_penjualan" id="id_penjualan" value="{{ $id_penjualan ?? '' }}">
                <input type="hidden" name="id_produk" id="id_produk">
                <input type="text" class="form-control" name="kode_produk" id="kode_produk" onkeydown="cekKodeProduk(event)">
                <span class="input-group-btn">
                    <button onclick="tampilProduk()" class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right"></i></button>
                </span>
            </div>
        </div>
    </div>
</form>
                <table class="table table-stiped table-bordered table-penjualan">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Harga</th>
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
                        <form action="{{ route('transaksi.simpan') }}" class="form-penjualan" method="post">
                            @csrf
                            <input type="hidden" name="id_penjualan" value="{{ $id_penjualan ?? '' }}">
                            <input type="hidden" name="total" id="total">
                            <input type="hidden" name="total_item" id="total_item">
                            <input type="hidden" name="bayar" id="bayar">
                            <input type="hidden" name="id_member" id="id_member" value="{{ $memberSelected->id_member }}">
                            <input type="hidden" name="diskon_value" id="diskon_value" value="{{ $memberSelected->diskon }}">
                            <input type="hidden" name="diskon_type" id="diskon_type" value="{{ $memberSelected->diskon_type ?? 'percent' }}">
                            <input type="hidden" name="metode" id="metode" value="cash">
                            <div class="form-group row">
                                <label for="totalrp" class="col-lg-2 control-label">Total</label>
                                <div class="col-lg-8">
                                    <input type="text" id="totalrp" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kode_member" class="col-lg-2 control-label">Member</label>
                                <div class="col-lg-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="kode_member" value="{{ $memberSelected->kode_member }}">
                                        <span class="input-group-btn">
                                            <button onclick="tampilMember()" class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                        <label for="diskon" class="col-lg-2 control-label">Diskon</label>
                        <div class="col-lg-8">
                            @php
                                $memberSelected = session('member_selected') ?? null;
                                $diskonValue = $memberSelected->diskon ?? 0;
                                $diskonType = $memberSelected->diskon_type ?? 'percent';
                                
                                if ($diskonType === 'percent') {
                                    $diskonDisplay = $diskonValue . ' %';
                                } else {
                                    $diskonDisplay = 'Rp ' . number_format($diskonValue, 0, ',', '.');
                                }
                            @endphp
                            <input type="text" id="diskon" class="form-control" readonly value="{{ $diskonValue > 0 ? $diskonDisplay : '-' }}">    
                            <input type="hidden" name="diskon" id="diskon_value" value="{{ $diskonValue }}"> 
                            <input type="hidden" name="diskon_type" id="diskon_type" value="{{ $diskonType }}">
                        </div>
                            </div>
                            <div class="form-group row">
                                <label for="bayar" class="col-lg-2 control-label">Bayar</label>
                                <div class="col-lg-8">
                                    <input type="text" id="bayarrp" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="diterima" class="col-lg-2 control-label">Diterima</label>
                                <div class="col-lg-8">
                                    <input type="number" id="diterima" class="form-control" name="diterima" value="{{ $penjualan->diterima ?? 0 }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kembali" class="col-lg-2 control-label">Kembali</label>
                                <div class="col-lg-8">
                                    <input type="text" id="kembali" name="kembali" class="form-control" value="0" readonly>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
         <div class="box-footer text-right">
    <button type="submit" class="btn btn-primary btn-sm btn-flat btn-simpan" id="pay-button">
        <i class="fas fa-dollar-sign"></i> Cash
    </button>
    <button id="btn-bayar-qris" class="btn btn-success btn-sm">
        <i class="fa fa-qrcode"></i> QRIS
    </button>
</div>

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <script src="https://app.sandbox.midtrans.com/snap/snap.js"
          data-client-key="{{ config('services.midtrans.client_key') }}"></script>
          <script>
let table, table2;
$(function () {
    const btnQris = $('#btn-bayar-qris');

    btnQris.on('click', function () {
        if (btnQris.prop('disabled')) return;  

        const bayar = parseInt($('#bayar').val()) || 0;
        
        // Validasi input kosong
        if (bayar <= 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan!',
                text: 'Total bayar tidak valid. Mohon tambahkan produk terlebih dahulu.',
                showConfirmButton: false,
                timer: 3000
            });
            return;
        }

        if (bayar < 1000) {
            Swal.fire({
                icon: 'warning',
                title: 'Nominal Terlalu Kecil!',
                text: 'Nominal harus ≥ 1.000',
                showConfirmButton: false,
                timer: 3000
            });
            return;
        }

        btnQris.prop('disabled', true).text('Memproses…');

        $.post('/snap-token', {
            _token: '{{ csrf_token() }}',
            id_penjualan: '{{ $id_penjualan ?? '' }}'
        })
        .done(res => {
            if (!res.success) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: res.message,
                    showConfirmButton: false,
                    timer: 3000
                });
                btnQris.prop('disabled', false).text('Bayar QRIS');
                return;
            }

            snap.pay(res.token, {
                onClose: () => {
                    btnQris.prop('disabled', false).text('Bayar QRIS');
                },
                onError: e => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Pembayaran Gagal!',
                        text: e.status_message,
                        showConfirmButton: false,
                        timer: 3000
                    });
                    btnQris.prop('disabled', false).text('Bayar QRIS');
                },
                onSuccess: function (r) {
                    simpanTransaksiQR();
                },
                onPending: function (r) {
                    simpanTransaksiQR();
                }
            });
        })
        .fail(() => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Gagal membuat token',
                showConfirmButton: false,
                timer: 3000
            });
            btnQris.prop('disabled', false).text('Bayar QRIS');
        });

        function simpanTransaksiQR() {
            const formData = {
                _token: '{{ csrf_token() }}',
                id_penjualan: '{{ $id_penjualan ?? '' }}',
                total: $('#total').val(),
                total_item: $('#total_item').val(),
                bayar: $('#bayar').val(),
                diterima: $('#diterima').val(),
                id_member: $('#id_member').val() || '',
                diskon: $('#diskon_value').val() || 0,
                diskon_type: $('#diskon_type').val() || 'percent', 
                metode: 'QRIS'
            };

            console.log('Data QRIS yang akan dikirim:', formData);

            $.post('{{ route('transaksi.simpan') }}', formData)
            .done((response) => {
                if (response.success) {
                    location.href = response.redirect;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message,
                        showConfirmButton: true
                    });
                    btnQris.prop('disabled', false).text('Bayar QRIS');
                }
            })
            .fail(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Gagal menyimpan transaksi QR',
                    showConfirmButton: true
                });
                btnQris.prop('disabled', false).text('Bayar QRIS');
            });
        }
    });
});

$(function () {
    $('body').addClass('sidebar-collapse');

    // Debug: cek initial values
    console.log('Initial values check:');
    console.log('ID Penjualan:', '{{ $id_penjualan ?? '' }}');
    console.log('Member selected:', {
        id: '{{ $memberSelected->id_member ?? "" }}',
        kode: '{{ $memberSelected->kode_member ?? "" }}',
        diskon: '{{ $memberSelected->diskon ?? 0 }}'
    });

    table = $('.table-penjualan').DataTable({
        processing: true,
        autoWidth: false,
        ajax: {
            url: '{{ route('transaksi.data', $id_penjualan ?? 0) }}',
        },
        columns: [
            {data: 'DT_RowIndex', searchable: false, sortable: false},
            {data: 'kode_produk'},
            {data: 'nama_produk'},
            {data: 'harga_jual'},
            {data: 'jumlah'},
            {data: 'subtotal'},
            {data: 'aksi', searchable: false, sortable: false},
        ],
        dom: 'Brt',
        bSort: false,
        paginate: false
    })
    .on('draw.dt', function (e, settings, json) {
        console.log('DataTable reloaded, calling loadForm...');
        
        // Update hidden inputs dengan data dari server response
        if (json && json.total !== undefined) {
            $('#total').val(json.total);
            $('#total_item').val(json.total_item);
        } else {
            // Fallback: ambil dari DOM
            $('#total').val($('.total').text() || 0);
            $('#total_item').val($('.total_item').text() || 0);
        }
        
        loadForm($('#diskon_value').val() || 0);
        setTimeout(() => {
            $('#diterima').trigger('input');
        }, 300);
    });

    table2 = $('.table-produk').DataTable();

    // Load form pertama kali
    setTimeout(() => {
        console.log('Loading form on page load...');
        loadForm($('#diskon_value').val() || 0, $('#diterima').val() || 0);
    }, 500);

   $('.btn-simpan').on('click', function (e) {
    e.preventDefault();

    const bayar     = parseInt($('#bayar').val()) || 0;
    const diterima  = parseInt($('#diterima').val()) || 0;

    // Validasi input kosong
    if (bayar <= 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan!',
            text: 'Total bayar tidak valid. Mohon tambahkan produk terlebih dahulu.',
            showConfirmButton: false,
            timer: 3000
        });
        return;
    }

    if (diterima < bayar) {
        Swal.fire({
            icon: 'warning',
            title: 'Uang Tidak Cukup!',
            text: 'Uang diterima tidak cukup untuk memproses transaksi!',
            showConfirmButton: false,
            timer: 3000
        });
        $('#diterima').focus();
        return;
    }

    const btn = $(this);
    btn.prop('disabled', true).text('Memproses...');

    // Debug data yang akan dikirim
    const formData = {
        _token: '{{ csrf_token() }}',
        id_penjualan: '{{ $id_penjualan ?? '' }}',
        total: $('#total').val(),
        total_item: $('#total_item').val(),
        bayar: bayar,
        diterima: diterima,
        id_member: $('#id_member').val() || '',
        diskon: $('#diskon_value').val() || 0,
        diskon_type: $('#diskon_type').val() || 'percent',
        metode: 'cash'
    };

    console.log('Data yang akan dikirim:', formData);

    $.post('{{ route('transaksi.simpan') }}', formData)
    .done((response) => {
        if (response.success) {
            location.href = response.redirect;
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: response.message,
                showConfirmButton: true
            });
            btn.prop('disabled', false).text('Cash');
        }
    })
    .fail((xhr) => {
        console.error('Error response:', xhr.responseJSON);
        let msg = xhr.responseJSON?.message || 'Gagal menyimpan transaksi Cash';
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: msg,
            showConfirmButton: true
        });
        btn.prop('disabled', false).text('Cash');
    });
});



    $(document).on('input', '.quantity', function () {
        let id = $(this).data('id');
        let jumlah = parseInt($(this).val());

        if (jumlah < 1) {
            $(this).val(1);
            Swal.fire({
                icon: 'warning',
                title: 'Jumlah Tidak Valid!',
                text: 'Jumlah tidak boleh kurang dari 1',
                showConfirmButton: false,
                timer: 3000
            });
            return;
        }

        if (jumlah > 10000) {
            $(this).val(10000);
            Swal.fire({
                icon: 'warning',
                title: 'Jumlah Terlalu Besar!',
                text: 'Jumlah tidak boleh lebih dari 10000',
                showConfirmButton: false,
                timer: 3000
            });
            return;
        }

        $.post(`{{ url('/transaksi') }}/${id}`, {
            '_token': $('[name=csrf-token]').attr('content'),
            '_method': 'put',
            'jumlah': jumlah
        })
        .done(response => {
            table.ajax.reload(() => loadForm($('#diskon').val()));
        })
        .fail(xhr => {
    let response = xhr.responseJSON;
    if (response && response.stok !== undefined) {
        Swal.fire({
            icon: 'warning',
            title: 'Stok Terbatas!',
            text: 'Stok terbatas: hanya tersedia ' + response.stok,
            showConfirmButton: false,
            timer: 3000
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Gagal menyimpan data',
            showConfirmButton: false,
            timer: 3000
        });
    }
});
    });

    $(document).on('input', '#diskon', function () {
        if ($(this).val() === "") {
            $(this).val(0).select();
        }
        loadForm($(this).val());
    });

    $('#diterima').on('input', function () {
        if ($(this).val() === "") {
            $(this).val(0).select();
        }
        loadForm($('#diskon_value').val(), $(this).val());
        
    }).focus(function () {
        $(this).select();
    });
});

function cekKodeProduk(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        cariProdukByKode();
    }
}

function cariProdukByKode() {
    let kode = $('#kode_produk').val();

    if (!kode) return;

    $.ajax({
        url: "{{ route('produk.getKode') }}",
        type: 'GET',
        data: { kode_produk: kode }, 
        success: function(response) {
            if (response.success) {
                $('#id_produk').val(response.data.id_produk);
                tambahProduk();
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Produk Tidak Ditemukan!',
                    text: 'Produk tidak ditemukan!',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Gagal mengambil data produk',
                showConfirmButton: false,
                timer: 3000
            });
        }
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
    $.post('{{ route('transaksi.store') }}', $('.form-produk').serialize())
        .done(response => {
            $('#kode_produk').val('').focus();
            
            // Jika ada id_penjualan baru dari response, update form
            if (response.id_penjualan && !$('#id_penjualan').val()) {
                $('#id_penjualan').val(response.id_penjualan);
                // Reload halaman untuk memperbarui semua referensi id_penjualan
                window.location.reload();
            } else {
                table.ajax.reload(() => loadForm($('#diskon').val()));
            }
        })
       .fail(xhr => {
    let response = xhr.responseJSON;
    if (response && response.message) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: response.message,
            showConfirmButton: false,
            timer: 3000
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Gagal menyimpan data.',
            showConfirmButton: false,
            timer: 3000
        });
    }
});

}

function tampilMember() {
    console.log('Opening member modal...');
    $('#modal-member').modal('show');
}

function pilihMember(id, kode) {
    console.log('Attempting to select member:', id, kode);
    $.get(`/member/${id}`, function(res) {
        console.log('Member data received:', res);
        $('#id_member').val(res.id_member);
        $('#kode_member').val(res.kode_member);
        $('#diskon_value').val(res.diskon);             // hidden input
        $('#diskon_type').val(res.diskon_type);         // hidden input

        // tampilkan ke user
        if (res.diskon_type === 'percent') {
            $('#diskon').val(res.diskon + ' %');
        } else if (res.diskon_type === 'nominal') {
            let nominal = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(res.diskon);
            $('#diskon').val(nominal);
        } else {
            $('#diskon').val('-');
        }

        // Call loadForm with correct parameters including discount type
        loadForm($('#diskon_value').val() || 0, $('#diterima').val() || 0);
        $('#diterima').val(0).focus().select();
        hideMember();
    }).fail(function(xhr, status, error) {
        console.error('Failed to get member data:', xhr.status, error);
        console.error('Response:', xhr.responseText);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Gagal memuat data member: ' + error,
            showConfirmButton: true
        });
    });
}




function hideMember() {
    $('#modal-member').modal('hide');
}

function deleteData(url) {
    if (confirm('Yakin ingin menghapus data terpilih?')) {
        $.post(url, {
            '_token': $('[name=csrf-token]').attr('content'),
            '_method': 'delete'
        })
        .done(() => {
            table.ajax.reload(() => loadForm($('#diskon').val()));
        })
        .fail(() => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Tidak dapat menghapus data',
                showConfirmButton: false,
                timer: 3000
            });
        });
    }
}

function loadForm(diskon = 0, diterima = 0) {
    // Ambil total dari hidden input
    let total = parseFloat($('#total').val()) || 0;
    
    // Jika total masih 0, ambil dari elemen .total
    if (total === 0) {
        const totalText = $('.total').text().trim();
        total = parseFloat(totalText) || 0;
    }
    
    console.log('LoadForm called with:', {diskon, diterima, total});

    if (total === 0) {
        console.log('Total is 0, skipping loadForm');
        return;
    }

    $('#total').val(total);
    $('#total_item').val($('.total_item').text() || 0);

    $.get(`{{ url('/transaksi/loadform') }}`, {
        diskon: diskon,
        total: total,
        diterima: diterima,
        type: $('#diskon_type').val() || 'percentage'
    })
    .done(response => {
        console.log('LoadForm response:', response);
        $('#totalrp').val('Rp. '+ response.totalrp);
        $('#bayarrp').val('Rp. '+ response.bayarrp);
        $('#bayar').val(response.bayar);
        $('.tampil-bayar').text('Bayar: Rp. '+ response.bayarrp);
        $('.tampil-terbilang').text(response.terbilang);

        $('#kembali').val('Rp.'+ response.kembalirp);
        if ($('#diterima').val() != 0) {
            $('.tampil-bayar').text('Kembali: Rp. '+ response.kembalirp);
            $('.tampil-terbilang').text(response.kembali_terbilang);
        }
    })
    .fail((xhr, status, error) => {
        console.error('LoadForm error:', xhr.responseText, status, error);
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Tidak dapat menampilkan data',
            showConfirmButton: false,
            timer: 3000
        });
    });
}


</script>
@endpush



@includeIf('penjualan_detail.produk')
@includeIf('penjualan_detail.member')
@endsection

