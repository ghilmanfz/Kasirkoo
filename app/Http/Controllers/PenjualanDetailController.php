<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use Closure;

class PenjualanDetailController extends Controller
{
    
    public function index()
    {
        $produk = Produk::orderBy('nama_produk')->get();
        $member = Member::orderBy('nama')->get();
        
        $diskon = Member::first()->diskon ?? 0;
        

        // Cek apakah ada transaksi yang sedang berjalan
        if ($id_penjualan = session('id_penjualan')) {
            $penjualan = Penjualan::find($id_penjualan);
            $memberSelected = $penjualan->member ?? new Member();

            return view('penjualan_detail.index', compact('produk', 'member', 'diskon', 'id_penjualan', 'penjualan', 'memberSelected'));
        } else {
            // Baik admin (level 1) maupun kasir (level 0) bisa melakukan transaksi
            if (auth()->user()->level == 1 || auth()->user()->level == 0) {
                // Tidak membuat transaksi otomatis, biarkan user memulai transaksi kosong
                $id_penjualan = null;
                $penjualan = null;
                $memberSelected = new Member();
                return view('penjualan_detail.index', compact('produk', 'member', 'diskon', 'id_penjualan', 'penjualan', 'memberSelected'));
            } else {
                return redirect()->route('home');
            }
        }
    }

    public function data($id = null)
    {
        if (!$id) {
            // Jika tidak ada id transaksi, kembalikan data kosong
            return datatables()->of([])->make(true);
        }

        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', $id)
            ->get();

        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['kode_produk'] = '<span class="label label-success">'. $item->produk['kode_produk'] .'</span';
            $row['nama_produk'] = $item->produk['nama_produk'];
            $row['harga_jual']  = 'Rp. '. format_uang($item->harga_jual);
            $row['jumlah']      = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->id_penjualan_detail .'" value="'. $item->jumlah .'">';
            $row['subtotal']    = 'Rp. '. format_uang($item->subtotal);
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`'. route('transaksi.destroy', $item->id_penjualan_detail) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

            $total += $item->harga_jual * $item->jumlah;
            $total_item += $item->jumlah;
        }
        $data[] = [
            'kode_produk' => '
                <div class="total hide">'. $total .'</div>
                <div class="total_item hide">'. $total_item .'</div>',
            'nama_produk' => '',
            'harga_jual'  => '',
            'jumlah'      => '',
            'subtotal'    => '',
            'aksi'        => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'kode_produk', 'jumlah'])
            ->with([
                'total' => $total,
                'total_item' => $total_item
            ])
            ->make(true);
    }


public function snapToken(Request $request)
{
    $order  = Penjualan::with('detail')->findOrFail($request->id_penjualan);
    $gross  = (int) $order->detail->sum('subtotal');

    if ($gross < 1000) {
        return response()->json([
            'success' => false,
            'message' => 'Minimal transaksi QRIS Rp 1.000'
        ], 422);
    }

    $orderId = 'POS-' . $order->id_penjualan . '-' . now()->format('His');

    Config::$serverKey    = config('services.midtrans.server_key');
    Config::$isProduction = config('services.midtrans.is_production');
    Config::$isSanitized  = config('services.midtrans.is_sanitized');
    Config::$is3ds        = config('services.midtrans.is_3ds');

    $params = [
        'transaction_details' => [
            'order_id'     => $orderId,
            'gross_amount' => $gross,
        ],
        'enabled_payments'  => ['gopay','bank_transfer','qris'],   // hapus baris ini jika ingin seluruh kanal
        'customer_details'  => [
            'first_name' => auth()->user()->name ?? 'Guest',
            'email'      => auth()->user()->email ?? 'guest@example.com',
        ],
    ];

    try {
        $token = Snap::getSnapToken($params);

        $order->update([
            'snap_token'     => $token,
            'order_midtrans' => $orderId,
        ]);

        return response()->json(['success' => true, 'token' => $token]);
    } catch (\Exception $e) {
        \Log::error('SnapToken Error: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
} 


  public function update(Request $request, $id)
{
    $detail = PenjualanDetail::findOrFail($id);
    $produk = Produk::findOrFail($detail->id_produk);

    // Cek stok: total yang diminta <= stok
    if ($request->jumlah > $produk->stok) {
    return response()->json([
        'success' => false,
        'message' => 'Stok tidak mencukupi. Stok tersedia hanya '.$produk->stok,
        'stok'    => $produk->stok
    ], 422);
}

    $detail->jumlah   = (int) $request->jumlah;
    $detail->subtotal = $detail->harga_jual * $detail->jumlah;
    $detail->save();

    // hitung ulang header
    $penjualan = Penjualan::with('detail')->findOrFail($detail->id_penjualan);
    $total_item  = $penjualan->detail->sum('jumlah');
    $total_harga = $penjualan->detail->sum('subtotal');
    $diskon  = (float) $penjualan->diskon; 
    $bayar   = $total_harga - ($diskon/100 * $total_harga);

    $penjualan->update([
        'total_item'  => $total_item,
        'total_harga' => $total_harga,
        'bayar'       => $bayar,
    ]);

    return response()->json([
        'success'      => true,
        'total_item'   => $total_item,
        'total_harga'  => format_uang($total_harga),
        'bayar'        => format_uang($bayar),
         'stok' => $produk->stok
    ]);
}



    public function destroy($id)
    {
        $detail = PenjualanDetail::find($id);
        $detail->delete();

        return response(null, 204);
    }

   /**
 * Hitung total bayar & kembalian
 *
 * @param float  $diskon        Nilai diskon (angka saja)
 * @param float  $total         Total sebelum diskon
 * @param float  $diterima      Uang yang diterima kasir
 * @param string $diskonType    'percent' | 'nominal'
 */
    public function loadForm($diskon = 0, $total = 0, $diterima = 0)
    {
        try {
            // Check if parameters are passed via query string
            if ($diskon == 0 && $total == 0 && $diterima == 0) {
                $diskon = request('diskon', 0);
                $total = request('total', 0);
                $diterima = request('diterima', 0);
            }
            
            // Ensure numeric values
            $diskon = (float) $diskon;
            $total = (float) $total;
            $diterima = (float) $diterima;
            
            $type = request('type', 'percent'); // Default to percent
            
            // Calculate discount based on type
            if ($type === 'nominal') {
                // Fixed amount discount (e.g., Rp 8000)
                $bayar = $total - $diskon;
            } else {
                // Percentage discount (e.g., 10%)
                $bayar = $total - ($diskon / 100 * $total);
            }
            
            // Ensure bayar is not negative
            $bayar = max(0, $bayar);
            $kembali = ($diterima != 0) ? $diterima - $bayar : 0;
            
            $data = [
                'totalrp' => format_uang($total),
                'bayar' => $bayar,
                'bayarrp' => format_uang($bayar),
                'terbilang' => ucwords(terbilang($bayar). ' Rupiah'),
                'kembalirp' => format_uang($kembali),
                'kembali_terbilang' => ucwords(terbilang($kembali). ' Rupiah'),
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load form data: ' . $e->getMessage()], 500);
        }
    }

public function getToken(Request $request)
{
    // Validasi minimal
    $request->validate([
        'id_penjualan' => 'required|exists:penjualan,id_penjualan',
    ]);

    // Ambil header transaksi
    $penjualan = Penjualan::findOrFail($request->id_penjualan);

    // Hitung total riil dari detail
    $sumDetail = (int) PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)
                  ->sum(DB::raw('harga_jual * jumlah'));

    // Dapatkan diskon global atau member jika perlu
    $diskon = Setting::first()->diskon ?? 0;
    $total  = $sumDetail - ($sumDetail * $diskon / 100);

    if ($total < 1) {
        return response()->json([
            'error' => true,
            'message' => 'Belum ada produk di keranjang.',
        ], 422);
    }

    // Konfigurasi Midtrans
 
    return response()->json('Data berhasil disimpan', 200);
} 
public function midtransCallback(Request $req)
{
    Config::$serverKey = config('services.midtrans.server_key');

    $notif = new \Midtrans\Notification();   // otomatis verifikasi signature

    $trx = Penjualan::where('order_midtrans', $notif->order_id)->first();
    if (!$trx) return response()->json([],404);

    switch ($notif->transaction_status) {
        case 'settlement':
            $trx->update([
                'status'       => 'paid',
                'payment_type' => $notif->payment_type,
                'diterima'     => $notif->gross_amount,
                'kembali'      => 0,
                'paid_at'      => now(),
             'metode_pembayaran' => strtoupper($notif->payment_type ?? 'QRIS')
            ]);
            break;

        case 'pending':
            // biarkan status = pending
            break;

        case 'expire':
        case 'cancel':
            $trx->update(['status'=>'canceled']);
            break;
    }
    
    return response()->json(['message'=>'ok']);

}

public function bayarQR(Request $request)
{
    $request->validate([
    'id_penjualan' => 'required|exists:penjualan,id_penjualan',
    'total'        => 'required|numeric|min:1',
    
]);

    $penjualan = Penjualan::find($request->id_penjualan);

    // Konfigurasi Midtrans
    \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
    \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;

    $params = [
        'transaction_details' => [
            'order_id' => $penjualan->id_penjualan,
            'gross_amount' => (int) $request->bayar,
        ],
        'enabled_payments' => ['qris'],
        'customer_details' => [
            'first_name' => auth()->user()->name,
            'email' => auth()->user()->email ?? 'admin@example.com',
        ]
    ];

    try {
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        return response()->json([
            'success' => true,
            'token' => $snapToken,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal membuat Snap Token: '.$e->getMessage(),
        ]);
    }
}


public function store(Request $request)
{
    $produk = Produk::where('id_produk', $request->id_produk)->first();
    if (! $produk) {
        return response()->json(['success'=>false, 'message'=>'Produk tidak ditemukan'], 400);
    }
    if ($produk->stok < 1) {
        return response()->json(['success'=>false, 'message'=>'Stok produk habis '], 400);
    }

    // Cek apakah ada transaksi aktif, jika tidak buat baru
    $id_penjualan = $request->id_penjualan;
    if (!$id_penjualan || !session('id_penjualan')) {
        // Buat transaksi baru
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
        $id_penjualan = $penjualan->id_penjualan;
    }

    $detail = new PenjualanDetail();
    $detail->id_penjualan = $id_penjualan;
    $detail->id_produk    = $produk->id_produk;
    $detail->harga_jual   = $produk->harga_jual;
    $detail->jumlah       = 1;
    $detail->diskon       = 0;
    $detail->subtotal     = $produk->harga_jual;
    $detail->save();

    return response()->json(['success'=>true, 'id_penjualan' => $id_penjualan]);
}

public function getKode(Request $request)
{
    $produk = Produk::where('kode_produk', $request->kode_produk)->first();

    if (! $produk) {
        return response()->json([
            'success' => false,
            'message' => 'Produk tidak ditemukan'
        ]);
    }

    return response()->json([
        'success' => true,
        'data' => [
            'id_produk' => $produk->id_produk,
            'kode_produk' => $produk->kode_produk,
            'stok' => $produk->stok, // 
        ]
    ]);


}
public function simpanTransaksi(Request $request)
{
    $request->validate([
        'id_penjualan' => 'required|exists:penjualan,id_penjualan',
        'bayar' => 'required|numeric|min:1',
        'diterima' => 'required|numeric|min:1',
        'diskon'       => 'required|numeric|min:0',
        'diskon_type'  => 'required|in:percent,nominal',
    ]);

    $penjualan = Penjualan::findOrFail($request->id_penjualan);

   $penjualan->update([
    'total_item'     => $request->total_item,
    'total_harga'    => $request->total,
    'diskon'         => $request->diskon,
    'diskon_type'    => $request->diskon_type,
    'bayar'          => $request->bayar,
    'diterima'       => $request->diterima,
    'kembali'        => $request->diterima - $request->bayar,
    'status'         => 'paid',
    'payment_type'   => 'cash',
    'metode_pembayaran' => 'CASH', 
    'paid_at'        => now(),
]);
    session()->forget('id_penjualan');

    return response()->json(['success' => true]);
}
public function show($id)
{
    $member = Member::find($id);

    if (!$member) {
        return response()->json(['success' => false, 'message' => 'Member tidak ditemukan']);
    }

    return response()->json([
        'success' => true,
        'id_member' => $member->id_member,
        'kode_member' => $member->kode_member,
        'diskon' => $member->diskon,
        'diskon_type' => $member->diskon_type, 
    ]);
}



public function stokRendah()
{
    $produk = Produk::where('stok', '<', 7)->get(['nama_produk', 'stok']);

    return response()->json([
        'success' => true,
        'data' => $produk
    ]);
}

}


