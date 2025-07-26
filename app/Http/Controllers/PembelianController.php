<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Produk;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    public function index()
    {
        $supplier = Supplier::orderBy('nama')->get();

        return view('pembelian.index', compact('supplier'));
    }

    public function data()
    {
        $pembelian = Pembelian::orderBy('id_pembelian', 'desc')->get();

        return datatables()
            ->of($pembelian)
            ->addColumn('total_item', function ($pembelian) {
                return format_uang($pembelian->total_item);
            })
            ->addColumn('total_harga', function ($pembelian) {
                return 'Rp. '. format_uang($pembelian->total_harga);
            })
            ->addColumn('bayar', function ($pembelian) {
                return 'Rp. '. format_uang($pembelian->bayar);
            })
            ->addColumn('tanggal', function ($pembelian) {
                return tanggal_indonesia($pembelian->created_at, false);
            })
            ->addColumn('supplier', function ($pembelian) {
                return $pembelian->supplier->nama;
            })
            ->addColumn('status_badge', function ($p) {
            return $p->status === 'sukses'
                ? '<span class="label label-success">Sukses</span>'
                : '<span class="label label-warning">Pending</span>';
        })
            ->editColumn('diskon', function ($pembelian) {
                return $pembelian->diskon . '%';
            })
            ->addColumn('aksi', function ($p) {
    // tombol terima barang
    $receiveBtn = $p->status === 'pending'
        ? '<button onclick="receiveData(`'.route('pembelian.receive',$p->id_pembelian).'`)" class="btn btn-xs btn-primary"><i class="fa fa-check"></i></button>'
        : '<span class="label label-success">Sukses</span>';

    // tombol edit hanya saat pending
    $editBtn = $p->status === 'pending'
        ? '<a href="'.route('pembelian.edit',$p->id_pembelian).'" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i></a>'
        : '';

    return '<div class="btn-group">'.$receiveBtn.$editBtn.'
        <button onclick="showDetail(`'.route('pembelian.show',$p->id_pembelian).'`)" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></button>
        <button onclick="deleteData(`'.route('pembelian.destroy',$p->id_pembelian).'`)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
    </div>';
})
->rawColumns(['status_badge','aksi'])

    ->make(true);
    }

   public function create($id)
{
    $pembelian = Pembelian::create([
        'id_supplier' => $id,
        'total_item'  => 0,
        'total_harga' => 0,
        'diskon'      => 0,
        'bayar'       => 0,
        'status'      => 'pending',   
    ]);

    session(['id_pembelian' => $pembelian->id_pembelian,
             'id_supplier'  => $pembelian->id_supplier]);

    return redirect()->route('pembelian_detail.index');
}

    public function store(Request $r)
{
    $pembelian = Pembelian::findOrFail($r->id_pembelian);
    $pembelian->update([
        'total_item'  => $r->total_item,
        'total_harga' => $r->total,
        'diskon'      => $r->diskon,
        'bayar'       => $r->bayar,
        // status tetap 'pending'
    ]);
    return redirect()->route('pembelian.index');
}

    public function show($id)
    {
        $detail = PembelianDetail::with('produk')->where('id_pembelian', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_produk', function ($detail) {
                return '<span class="label label-success">'. $detail->produk->kode_produk .'</span>';
            })
            ->addColumn('nama_produk', function ($detail) {
                return $detail->produk->nama_produk;
            })
            ->addColumn('harga_beli', function ($detail) {
                return 'Rp. '. format_uang($detail->harga_beli);
            })
            ->addColumn('jumlah', function ($detail) {
                return format_uang($detail->jumlah);
            })
            ->addColumn('subtotal', function ($detail) {
                return 'Rp. '. format_uang($detail->subtotal);
            })
            ->rawColumns(['kode_produk'])
            ->make(true);
    }

    public function destroy($id)
    {
        $pembelian = Pembelian::find($id);
        $detail    = PembelianDetail::where('id_pembelian', $pembelian->id_pembelian)->get();
        foreach ($detail as $item) {
            $produk = Produk::find($item->id_produk);
            if ($produk) {
                $produk->stok -= $item->jumlah;
                $produk->update();
            }
            $item->delete();
        }

        $pembelian->delete();

        return response(null, 204);
    }
public function receive($id){
    DB::transaction(function() use ($id){
        $p = Pembelian::with('detail')->findOrFail($id);
        if($p->status === 'sukses') abort(400,'Pembelian sudah sukses');

        $p->update(['status'=>'sukses','tgl_datang'=>now()]);

        foreach($p->detail as $d){
            Produk::where('id_produk',$d->id_produk)
                  ->increment('stok',$d->jumlah);
        }
    });

    return response()->json(['message'=>'Stok ditambahkan']);
}
public function edit($id)
{
    $p   = Pembelian::findOrFail($id);          // pastikan masih ada
    // simpan ke session supaya pembelian_detail.index tahu transaksi mana
    session([
        'id_pembelian' => $p->id_pembelian,
        'id_supplier'  => $p->id_supplier,
    ]);

    return redirect()->route('pembelian_detail.index');
}
}
