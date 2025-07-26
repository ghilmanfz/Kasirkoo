<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Produk;
use App\Models\Supplier;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;

class PembelianDetailController extends Controller
{
  
    public function index()
    {
        $id_pembelian = session('id_pembelian');
        $produk       = Produk::orderBy('nama_produk')->get();
        $supplier     = Supplier::find(session('id_supplier'));
        $diskon       = Pembelian::find($id_pembelian)->diskon ?? 0;

        if (! $supplier) {
            abort(404);
        }

        // Cukup kirim data yang dibutuhkan ke view, tanpa SnapToken
        return view('pembelian_detail.index', compact(
            'id_pembelian','produk','supplier','diskon'
        ));
    }

    public function data($id)
    {
        $detail = PembelianDetail::with('produk')
            ->where('id_pembelian', $id)
            ->get();

        return DataTables::of($detail)
            ->addIndexColumn()
            ->addColumn('kode_produk', fn($r) =>
                '<span class="label label-success">'. $r->produk->kode_produk .'</span>')
            ->addColumn('nama_produk', fn($r) =>
                $r->produk->nama_produk)
                ->addColumn('harga_beli', function ($r) {
    return '<input type="number" class="form-control input-sm harga-beli" '
         . 'data-id="'. $r->id_pembelian_detail .'" min="1" value="'. $r->harga_beli .'">';
})
            ->addColumn('jumlah', fn($r) =>
                '<input type="number" class="form-control input-sm quantity" '
              . 'data-id="'. $r->id_pembelian_detail .'" value="'. $r->jumlah .'">')
            ->addColumn('subtotal', fn($r) =>
                'Rp. '.format_uang($r->subtotal))
            ->addColumn('aksi', fn($r) =>
                '<button onclick="deleteData(\''.
                  route('pembelian_detail.destroy', $r->id_pembelian_detail).
                  '\')" class="btn btn-xs btn-danger btn-flat">'.
                  '<i class="fa fa-trash"></i></button>')
            ->rawColumns(['kode_produk','harga_beli','jumlah','aksi'])
            ->make(true);
    }
    public function store(Request $request)
    {
        $produk = Produk::where('id_produk', $request->id_produk)->first();
        if (! $produk) {
            return response()->json('Data gagal disimpan', 400);
        }

        $detail = new PembelianDetail();
        $detail->id_pembelian = $request->id_pembelian;
        $detail->id_produk = $produk->id_produk;
        $detail->harga_beli = $produk->harga_beli;
        $detail->jumlah = 1;
        $detail->subtotal = $produk->harga_beli;
        $detail->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function update(Request $request, $id)
{
    $detail = PembelianDetail::findOrFail($id);

    // Jika hanya jumlah yg dikirim
    if ($request->filled('jumlah')) {
        $detail->jumlah   = $request->jumlah;
    }

    // Jika harga dikirim
    if ($request->filled('harga_beli')) {
        $detail->harga_beli = $request->harga_beli;
    }

    // Hitung ulang subtotal
    $detail->subtotal = $detail->harga_beli * $detail->jumlah;
    $detail->save();

    return response()->json('OK');
}


    public function destroy($id)
    {
        $detail = PembelianDetail::find($id);
        $detail->delete();

        return response(null, 204);
    }

    public function loadForm($diskon, $total)
    {
        $bayar = $total - ($diskon / 100 * $total);
        $data  = [
            'totalrp' => format_uang($total),
            'bayar' => $bayar,
            'bayarrp' => format_uang($bayar),
            'terbilang' => ucwords(terbilang($bayar). ' Rupiah')
        ];

        return response()->json($data);
    }
    public function getKode(Request $r)
{
    $num = intval(preg_replace('/\D+/', '', $r->kode_produk));
    $prod = Produk::whereRaw('CAST(SUBSTRING(kode_produk, 2) AS UNSIGNED) = ?', [$num])
                  ->first();

    return response()->json([
        'success' => (bool) $prod,
        'data'    => $prod,
    ]);
}
}
