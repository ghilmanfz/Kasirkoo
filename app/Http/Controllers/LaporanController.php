<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Pengeluaran;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use PDF;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tanggalAwal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');

        if ($request->has('tanggal_awal') && $request->tanggal_awal != "" && $request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }

        return view('laporan.index', compact('tanggalAwal', 'tanggalAkhir'));
    }

public function getData($awal, $akhir)
{
    
    $no = 1;
    $totalPendapatan = 0;

    while (strtotime($awal) <= strtotime($akhir)) {
        $tanggal = $awal;
        $awal = date('Y-m-d', strtotime('+1 day', strtotime($awal)));

        $penjualan   = Penjualan::whereDate('created_at', $tanggal)->sum('bayar');
        $pembelian   = Pembelian::whereDate('created_at', $tanggal)->sum('bayar');
        $pengeluaran = Pengeluaran::whereDate('created_at', $tanggal)->sum('nominal');

        $pendapatan  = $penjualan - $pembelian - $pengeluaran;
        $totalPendapatan += $pendapatan;

        $rows[] = [
            'no'          => $no++,
            'tanggal'     => tanggal_indonesia($tanggal, false),
            'penjualan'   => $penjualan,
            'pembelian'   => $pembelian,
            'pengeluaran' => $pengeluaran,
            'keuntungan'  => $penjualan - $pembelian,
            'pendapatan'  => $pendapatan,
        ];
    }

    $rows[] = [
        'no'          => 'Total Pendapatan',
        'tanggal'     => '',
        'penjualan'   => '',
        'pembelian'   => '',
        'pengeluaran' => '',
        'keuntungan'  => '',
        'pendapatan'  => $totalPendapatan,
    ];

    return $rows;
}



public function data($awal, $akhir)
{
    $collection = collect($this->getData($awal, $akhir));

    return DataTables::of($collection)
        ->addColumn('DT_RowIndex', fn($r) => '<strong>' . $r['no'] . '</strong>')
        ->addColumn('tanggal', fn($r) => $r['tanggal'])
        ->addColumn('penjualan', fn($r) => is_numeric($r['penjualan'])
            ? 'Rp. '.format_uang($r['penjualan'])
            : '')
        ->addColumn('pembelian', fn($r) => is_numeric($r['pembelian'])
            ? 'Rp. '.format_uang($r['pembelian'])
            : '')
       ->addColumn('pengeluaran', fn($r) => is_numeric($r['pengeluaran'])
            ? 'Rp. '.format_uang($r['pengeluaran'])
            : '')
        ->addColumn('pendapatan', fn($r) => 'Rp. '.format_uang($r['pendapatan'] ?? 0))
        ->rawColumns(['DT_RowIndex']) 
        ->make(true);
}





 public function exportPDF($awal, $akhir)
{
    try {
        $start = Carbon::createFromFormat('Y-m-d', $awal)->startOfDay();
        $end   = Carbon::createFromFormat('Y-m-d', $akhir)->endOfDay();
    } catch (\Exception $e) {
        $start = Carbon::today()->startOfDay();
        $end   = Carbon::today()->endOfDay();
    }
    $dates = collect();
    for ($date = $start; $date->lte($end); $date->addDay()) {
        $d = $date->toDateString();
        $penjualan    = Penjualan::whereDate('created_at', $d)->sum('total_harga');
        $pembelian    = Pembelian::whereDate('created_at', $d)->sum('total_harga');
        $pengeluaran  = Pengeluaran::whereDate('created_at', $d)->sum('nominal');
        $pendapatan   = $penjualan - $pembelian - $pengeluaran;

        $dates->push((object)[
            'tanggal'    => $d,
            'penjualan'  => $penjualan,
            'pembelian'  => $pembelian,
            'pengeluaran'=> $pengeluaran,
            'pendapatan' => $pendapatan,
        ]);
    }
    $pdf = PDF::loadView('laporan.export_pdf', [
        'laporans'     => $dates,
        'tanggalAwal'  => $start->toDateString(),
        'tanggalAkhir' => $end->toDateString(),
    ])->setPaper('a4', 'landscape');
    return $pdf->stream("Laporan_{$start->format('Ymd')}_sampai_{$end->format('Ymd')}.pdf",
                        ['Attachment' => false]);
}
    
}
