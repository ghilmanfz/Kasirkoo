<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan {{ tanggal_indonesia($tanggalAwal, false) }} s/d {{ tanggal_indonesia($tanggalAkhir, false) }}</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        h3 { text-align: center; margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h3>LAPORAN PENDAPATAN<br>
        Periode {{ tanggal_indonesia($tanggalAwal, false) }}
        s/d {{ tanggal_indonesia($tanggalAkhir, false) }}
    </h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Penjualan</th>
                <th>Pengeluaran</th>
                <th>pembelian barang</th>
                <th>Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporans as $row)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ tanggal_indonesia($row->tanggal, false) }}</td>
                <td>Rp. {{ format_uang($row->penjualan) }}</td>
                <td>Rp. {{ format_uang($row->pembelian) }}</td>
                <td>RP. {{ format_uang($row->pengeluaran) }}</td>
                <td>Rp. {{ format_uang($row->pendapatan) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" style="text-align:right">Total Pendapatan</th>
                <th>Rp. {{ format_uang($laporans->sum('pendapatan')) }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
