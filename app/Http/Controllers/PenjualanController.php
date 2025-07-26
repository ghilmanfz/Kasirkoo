<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;

class PenjualanController extends Controller
{
    public function index()
    {
        return view('penjualan.index');
    }

    public function data()
    {
      $penjualan = Penjualan::with(['member', 'user'])->orderBy('id_penjualan', 'desc')->get();


        return datatables()
            ->of($penjualan)
            ->addColumn('total_item', function ($penjualan) {
                return format_uang($penjualan->total_item);
            })
            ->addColumn('kode_member', function ($p) {
    $kode = $p->member->kode_member ?? '';
    return '<span class="label label-success">'.$kode.'</span>';
})
            ->addColumn('total_harga', function ($penjualan) {
                return 'Rp. '. format_uang($penjualan->total_harga);
            })
            ->addColumn('bayar', function ($penjualan) {
                return 'Rp. '. format_uang($penjualan->bayar);
            })
           ->addColumn('tanggal', function ($penjualan) {
                // 15‑07‑2025  →  15 Juli 2025
                return tanggal_indonesia($penjualan->created_at, false);
            })
            ->addColumn('jam', function ($penjualan) {
                // 2025‑07‑15 14:35:22 → 14:35
                return $penjualan->created_at->format('H:i');
            })
            ->addColumn('kode_member', function ($penjualan) {
                $member = $penjualan->member->kode_member ?? '';
                return '<span class="label label-success">'. $member .'</spa>';
            })
            ->addColumn('diskon', function ($member) {
                 return $member->diskon_type === 'percent'
                   ? (int) $member->diskon . ' %'                   
                   : 'Rp ' . number_format($member->diskon, 0, ',', '.'); 
            })
            ->editColumn('kasir', function ($penjualan) {
                return $penjualan->user->name ?? '';
            })
            ->addColumn('metode_pembayaran', function ($penjualan) {
                return ucfirst($penjualan->metode_pembayaran ?? '-');
            })
          

            ->addColumn('aksi', function ($penjualan) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`'. route('penjualan.show', $penjualan->id_penjualan) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="printNota(`'. route('penjualan.notaBesar', $penjualan->id_penjualan) .'`)" class="btn btn-xs btn-warning btn-flat"><i class="fa fa-print"></i></button>
                    </div>
                ';
            })
            ->rawColumns(['aksi', 'kode_member'])
            ->make(true);
    }

    public function create()
    {
        $penjualan = new Penjualan();
        $penjualan->id_member = null;
        $penjualan->total_item = 0;
        $penjualan->total_harga = 0;
        $penjualan->diskon = 0;
        $penjualan->bayar = 0;
        $penjualan->diterima = 0;
        $penjualan->id_user = auth()->id();
        $penjualan->save();

        session(['id_penjualan' => $penjualan->id_penjualan]);
        return redirect()->route('transaksi.index');
    }

   public function store(Request $request)
{
    $penjualan = Penjualan::findOrFail($request->id_penjualan);
    $penjualan->id_member = $request->id_member;
    $penjualan->total_item = $request->total_item;
    $penjualan->total_harga = $request->total;
    $penjualan->diskon = $request->diskon;
    $penjualan->diskon_type = $request->diskon_type;
    $penjualan->bayar = $request->bayar;
    $penjualan->diterima = $request->diterima;
    $penjualan->metode_pembayaran = $request->metode; // Tambahkan ini
    $penjualan->update();

    // Update stok dan detail
    $detail = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
    foreach ($detail as $item) {
        $item->diskon = $request->diskon;
        $item->update();

        $produk = Produk::find($item->id_produk);
        $produk->stok -= $item->jumlah;
        $produk->update();
    }

    return redirect()->route('transaksi.selesai');
}

    public function show($id)
    {
        $detail = PenjualanDetail::with('produk')->where('id_penjualan', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_produk', function ($detail) {
                return '<span class="label label-success">'. $detail->produk->kode_produk .'</span>';
            })
            ->addColumn('nama_produk', function ($detail) {
                return $detail->produk->nama_produk;
            })
            ->addColumn('harga_jual', function ($detail) {
                return 'Rp. '. format_uang($detail->harga_jual);
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
        $penjualan = Penjualan::find($id);
        $detail    = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
        foreach ($detail as $item) {
            $produk = Produk::find($item->id_produk);
            if ($produk) {
                $produk->stok += $item->jumlah;
                $produk->update();
            }

            $item->delete();
        }

        $penjualan->delete();

        return response(null, 204);
    }

    public function selesai()
    {
        $setting = Setting::first();

        return view('penjualan.selesai', compact('setting'));
    }

    public function notaKecil()
    {
        $setting = Setting::first();
        $penjualan = Penjualan::find(session('id_penjualan'));
        if (! $penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', session('id_penjualan'))
            ->get();
        
        return view('penjualan.nota_kecil', compact('setting', 'penjualan', 'detail'));
    }


    public function notaBesar($id)
{
    $setting   = Setting::first();
    $penjualan = Penjualan::with('member','user','detail.produk')
        ->findOrFail($id);

    $detail    = PenjualanDetail::with('produk')
        ->where('id_penjualan', $id)
        ->get();

   return view('penjualan.nota_kecil', compact('setting', 'penjualan', 'detail'));
}
 public function exportAllPdf()
    {
        // ambil semua data penjualan beserta relasinya
        $penjualans = Penjualan::with('member','user','detail.produk')
                        ->orderBy('created_at', 'desc')
                        ->get();

        // load view, kirim variabel
        $pdf = PDF::loadView('penjualan.export_all', compact('penjualans'));
        // opsional: atur kertas (A4 landscape misalnya)
        $pdf->setPaper('a4', 'landscape');

        // stream ke browser
        return $pdf->stream('Laporan_Penjualan_Semua.pdf');
    }public function exportByDate($tanggal)
{
    try {
        $date = Carbon::createFromFormat('Y-m-d', $tanggal);
    } catch (\Exception $e) {
        $date = Carbon::today();
    }

    $penjualans = Penjualan::with('member','user','detail.produk')
        ->whereDate('created_at', $date->toDateString())
        ->orderBy('created_at', 'desc')
        ->get();
    $tanggalAwal = $date->toDateString();
    $tanggalAkhir = $date->toDateString();

    $pdf = PDF::loadView('penjualan.export_all', compact('penjualans', 'tanggalAwal', 'tanggalAkhir'))
              ->setPaper('a4', 'landscape');

    return $pdf->stream("Laporan_Penjualan_{$date->format('Ymd')}.pdf", ['Attachment' => false]);
}

function format_diskon($diskon, $diskon_type) {
    if ($diskon_type === 'percent') {
        return $diskon . ' %';
    } elseif ($diskon_type === 'nominal') {
        return format_uang($diskon);
    }
    return '-';
}

}






