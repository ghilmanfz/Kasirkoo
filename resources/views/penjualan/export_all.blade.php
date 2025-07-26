<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Semua Penjualan</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; }
        th { background: #eee; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h3 style="text-align:center;">LAPORAN PENJUALAN</h3>
 <h3 style="text-align:center;"><p><strong>Periode:</strong> {{ tanggal_indonesia($tanggalAwal, false) }} </p></h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Member</th>
                <th>Total Item</th>
                <th>Total Harga</th>
                <th>Diskon</th>
                <th>Bayar</th>
                <th>Kasir</th>
                <th>Metode</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualans as $row)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ tanggal_indonesia($row->created_at, false) }}</td>
                <td>{{ $row->member->kode_member ?? '-' }}</td>
                <td>{{ $row->total_item }}</td>
                <td>Rp. {{ format_uang($row->total_harga) }}</td>
                <td>
                    @if ($row->diskon_type == 'percent')
                        {{ $row->diskon }}%
                    @elseif ($row->diskon_type == 'nominal')
                        Rp. {{ format_uang($row->diskon) }}
                    @else
                        -
                    @endif
                </td>
                <td>Rp. {{ format_uang($row->bayar) }}</td>
                <td>{{ $row->user->name ?? '-' }}</td>
                <td>{{ ucfirst($row->metode_pembayaran) ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right">Total</th>
                <th>{{ $penjualans->sum('total_item') }}</th>
                <th> Rp. {{ format_uang($penjualans->sum('total_harga')) }}</th>
                <th>
            {{-- Jumlah diskon nominal --}}
          
            {{ $penjualans->where('diskon', '>', 0)->count() }} 
        </th>
                <th>Rp. {{ format_uang($penjualans->sum('bayar')) }}</th>
                <th colspan="2"></th>
            </tr>
        </tfoot>
    </table>

    {{-- Keterangan tambahan --}}
    <p><strong>Total Transaksi dengan Member:</strong> {{ $penjualans->whereNotNull('id_member')->count() }}</p>
</body>
</html>
