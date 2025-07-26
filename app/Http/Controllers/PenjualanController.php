<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Facades\Log;

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
            ->addColumn('diskon', function ($penjualan) {
                // Gunakan diskon dari transaksi yang tersimpan
                if ($penjualan->diskon > 0) {
                    return $penjualan->diskon_type === 'percent'
                        ? number_format($penjualan->diskon, 0) . ' %'                   
                        : 'Rp ' . number_format($penjualan->diskon, 0, ',', '.'); 
                }
                return '-';
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
                    <button onclick="deleteData(`'. route('penjualan.destroy', $penjualan->id_penjualan) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'kode_member'])
            ->make(true);
    }

    public function create()
    {
        // Hapus session penjualan sebelumnya untuk memulai transaksi baru
        session()->forget('id_penjualan');
        return redirect()->route('transaksi.index');
    }

   public function store(Request $request)
{
    try {
        // Validasi input dasar
        $request->validate([
            'id_penjualan' => 'required|integer',
            'total' => 'required|numeric|min:0',
            'total_item' => 'required|numeric|min:0',
            'bayar' => 'required|numeric|min:0',
            'diterima' => 'required|numeric|min:0',
            'metode' => 'required|string'
        ]);

        $penjualan = Penjualan::findOrFail($request->id_penjualan);
        
        // Cek apakah ada detail penjualan
        $detail_count = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->count();
        if ($detail_count == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada produk dalam transaksi. Silakan tambahkan produk terlebih dahulu.'
            ], 400);
        }

        $penjualan->id_member = $request->id_member ?? null;
        $penjualan->total_item = $request->total_item;
        $penjualan->total_harga = $request->total;
        $penjualan->diskon = $request->diskon ?? 0;
        $penjualan->diskon_type = $request->diskon_type ?? 'percent';
        $penjualan->bayar = $request->bayar;
        $penjualan->diterima = $request->diterima;
        $penjualan->metode_pembayaran = $request->metode;
        $penjualan->update();

        // Update stok produk
        $detail = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
        foreach ($detail as $item) {
            // Tidak perlu update diskon di detail karena diskon member adalah untuk keseluruhan transaksi
            // $item->diskon = $request->diskon ?? 0;
            // $item->update();

            $produk = Produk::find($item->id_produk);
            if ($produk) {
                $produk->stok -= $item->jumlah;
                $produk->update();
            }
        }

        // Return JSON response untuk AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'redirect' => route('transaksi.selesai')
            ]);
        }

        return redirect()->route('transaksi.selesai');
        
    } catch (\Exception $e) {
        Log::error('Error saving transaction: ' . $e->getMessage());
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage()
            ], 500);
        }
        
        return back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
    }
}

    public function show($id)
    {
        $detail = PenjualanDetail::with('produk')->where('id_penjualan', $id)->get();
        $penjualan = Penjualan::find($id);

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
            ->addColumn('diskon', function ($detail) use ($penjualan) {
                // Gunakan diskon member dari tabel penjualan
                if ($penjualan && $penjualan->diskon > 0) {
                    return $penjualan->diskon_type === 'percent'
                        ? number_format($penjualan->diskon, 0) . ' %'
                        : 'Rp ' . format_uang($penjualan->diskon);
                }
                return '-';
            })
            ->addColumn('jumlah', function ($detail) {
                return format_uang($detail->jumlah);
            })
            ->addColumn('subtotal', function ($detail) {
                return 'Rp. '. format_uang($detail->harga_jual * $detail->jumlah);
            })
            ->addColumn('bayar', function ($detail) use ($penjualan) {
                // Hitung bayar = subtotal - diskon member (proportional)
                $subtotal = $detail->harga_jual * $detail->jumlah;
                $diskon_amount = 0;
                
                if ($penjualan && $penjualan->diskon > 0) {
                    if ($penjualan->diskon_type === 'percent') {
                        // Diskon persen diterapkan langsung ke subtotal item
                        $diskon_amount = $subtotal * ($penjualan->diskon / 100);
                    } else {
                        // Diskon nominal dibagi proporsional berdasarkan total semua item
                        $total_semua_item = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)
                            ->sum(DB::raw('harga_jual * jumlah'));
                        
                        if ($total_semua_item > 0) {
                            $proporsi = $subtotal / $total_semua_item;
                            $diskon_amount = $penjualan->diskon * $proporsi;
                        }
                    }
                }
                
                $bayar = $subtotal - $diskon_amount;
                return 'Rp. '. format_uang(max(0, $bayar));
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






