<?php

use App\Http\Controllers\{
    DashboardController,
    KategoriController,
    LaporanController,
    ProdukController,
    MemberController,
    PengeluaranController,
    PembelianController,
    PembelianDetailController,
    PenjualanController,
    PenjualanDetailController,
    SettingController,
    SupplierController,
    UserController,
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
|
*/
 Route::group(['middleware' => 'level:1,0'], function () {
        Route::get('/transaksi/baru', [PenjualanController::class, 'create'])->name('transaksi.baru');
        Route::post('/transaksi/simpan', [PenjualanController::class, 'store'])->name('transaksi.simpan');
        Route::get('/transaksi/selesai', [PenjualanController::class, 'selesai'])->name('transaksi.selesai');
        Route::get('/transaksi/midtransCallback', [PenjualanController::class, 'midtransCallback'])->name('transaksi.midtransCallback');
        Route::get('/transaksi/nota-kecil', [PenjualanController::class, 'notaKecil'])->name('transaksi.nota_kecil');
        Route::get('/transaksi/nota-besar', [PenjualanController::class, 'notaBesar'])->name('transaksi.nota_besar');
Route::get('/produk/kode', [PenjualanDetailController::class, 'getKode'])
        ->name('produk.getKode');
        Route::get('/produk/kode', [PenjualanDetailController::class, 'getKode'])
     ->name('produk.getKode');
        Route::get('/transaksi/{id?}/data', [PenjualanDetailController::class, 'data'])->name('transaksi.data');
        Route::get('/transaksi/loadform/{diskon}/{total}/{diterima}', [PenjualanDetailController::class, 'loadForm'])->name('transaksi.load_form');
        Route::get('/transaksi/loadform', [PenjualanDetailController::class, 'loadForm'])->name('transaksi.loadform');
        Route::resource('/transaksi', PenjualanDetailController::class)
            ->except('create', 'show', 'edit');
            Route::get('/produk/stok-rendah', [ProdukController::class, 'stokRendah']);
            Route::get('penjualan/export/{tanggal}', [PenjualanController::class, 'exportByDate']);

                    Route::get('/penjualan/data', [PenjualanController::class, 'data'])->name('penjualan.data');
        Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
        Route::get('/penjualan/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');
        Route::delete('/penjualan/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');
        
        Route::get('/penjualan/data', [PenjualanController::class, 'data'])->name('penjualan.data');
        Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
        Route::get('/penjualan/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');
        Route::delete('/penjualan/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');
        Route::get('/member/data', [MemberController::class, 'data'])->name('member.data');
        Route::post('/member/cetak-member', [MemberController::class, 'cetakMember'])->name('member.cetak_member');
        Route::resource('/member', MemberController::class);
        
    });
Route::get('penjualan/export-all', [PenjualanController::class, 'exportAllPdf'])
     ->name('penjualan.export_all_pdf');
     Route::get('penjualan/export/{tanggal}', [PenjualanController::class, 'exportByDate'])
     ->name('penjualan.export_by_date_pdf');
     Route::get('penjualan/export/{tanggal}', [PenjualanController::class, 'exportByDate'])
     ->where('tanggal', '[0-9]{4}-[0-9]{2}-[0-9]{2}')
     ->name('penjualan.export_by_date_pdf');

Route::get('/', function () {
    return redirect()->route('login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['middleware' => 'level:1'], function () {
        Route::get('/kategori/data', [KategoriController::class, 'data'])->name('kategori.data');
        Route::resource('/kategori', KategoriController::class);

        Route::get('/produk/data', [ProdukController::class, 'data'])->name('produk.data');
        Route::post('/produk/delete-selected', [ProdukController::class, 'deleteSelected'])->name('produk.delete_selected');
        Route::post('/produk/cetak-barcode', [ProdukController::class, 'cetakBarcode'])->name('produk.cetak_barcode');
        Route::resource('/produk', ProdukController::class);

        

        Route::get('/supplier/data', [SupplierController::class, 'data'])->name('supplier.data');
        Route::resource('/supplier', SupplierController::class);

        Route::get('/pengeluaran/data', [PengeluaranController::class, 'data'])->name('pengeluaran.data');
        Route::resource('/pengeluaran', PengeluaranController::class);

        Route::get('/pembelian/data', [PembelianController::class, 'data'])->name('pembelian.data');
        Route::get('/pembelian/{id}/create', [PembelianController::class, 'create'])->name('pembelian.create');
        Route::resource('/pembelian', PembelianController::class)
            ->except('create');
            

        Route::get('/pembelian_detail/{id}/data', [PembelianDetailController::class, 'data'])->name('pembelian_detail.data');
        Route::get('/pembelian_detail/loadform/{diskon}/{total}', [PembelianDetailController::class, 'loadForm'])->name('pembelian_detail.load_form');
        Route::resource('/pembelian_detail', PembelianDetailController::class)
            ->except('create', 'show', 'edit');


    });

   

    Route::group(['middleware' => 'level:1,0'], function () {
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/data/{awal}/{akhir}', [LaporanController::class, 'data'])->name('laporan.data');
        Route::get('/laporan/pdf/{awal}/{akhir}', [LaporanController::class, 'exportPDF'])->name('laporan.export_pdf');
Route::get('/laporan/pdf/{awal}/{akhir}', [LaporanController::class, 'exportPDF'])
     ->name('laporan.export_pdf');
        Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
        Route::resource('/user', UserController::class);

        Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
        Route::get('/setting/first', [SettingController::class, 'show'])->name('setting.show');
        Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');
    });
 
    Route::group(['middleware' => 'level:1,0'], function () {
        Route::get('/profil', [UserController::class, 'profil'])->name('user.profil');
        Route::post('/profil', [UserController::class, 'updateProfil'])->name('user.update_profil');
        
        // Member access for cashier (read-only to select member in transactions)
        Route::get('/member/{id}', [MemberController::class, 'show'])->name('member.show_for_transaction');
    });
});
Route::get('penjualan/{id}/notaBesar', [PenjualanController::class, 'notaBesar'])
    ->name('penjualan.notaBesar');
Route::post('/transaksi/simpan-ajax', [PenjualanDetailController::class, 'simpanAjax']);
Route::post('/bayar-cash', [PenjualanDetailController::class, 'bayarCash']);
Route::post('/bayar-qr', [PenjualanDetailController::class, 'bayarQR']);
Route::post('/midtrans/callback', [PenjualanDetailController::class, 'midtransCallback']);



Route::post('/snap-token', [PenjualanDetailController::class, 'snapToken']);
Route::post('/bayar-cash', [PenjualanDetailController::class, 'bayarCash']);
Route::post('/spay',       [PenjualanDetailController::class, 'createSpay']); 
Route::put('pembelian/{pembelian}/receive',
           [PembelianController::class,'receive'])
     ->name('pembelian.receive');
     Route::get('pembelian/{pembelian}/edit',
    [PembelianController::class, 'edit'])
     ->name('pembelian.edit');

