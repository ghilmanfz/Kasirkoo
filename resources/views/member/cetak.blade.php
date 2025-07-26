<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Kartu Member</title>

    <style>
  .box {
    position: relative;
    width: 85.6mm;
    height: 54mm;
    margin: 5mm auto;
    border-radius: 3mm;
    overflow: hidden;
    box-shadow: 0 1mm 2mm rgba(0,0,0,0.3);
  }

  /* Background image full-cover */
  .box img {
    position: absolute;
    top: ; left: 0;
    width: 100% !important;
    height: 100% !important;
    object-fit: cover;
    display: block;
  }

  /* Logo perusahaan kiri atas */
  .logo {
    position: absolute;
    top: 40mm;
    left: 450mm;
    display: flex;
    align-items: center;
    gap: 1mm;
  }
  .logo p {
    margin: 0;
    font-size: 10pt;
    font-weight: bold;
    color: #fff;
  }
  .logo img {
    width: 12mm;
    height: 12mm;
    object-fit: contain;
  }

  /* Nama member (bottom-left) */
  .nama {
    position: absolute;
    bottom: 18mm;
    left: 4mm;
    font-size: 9pt;
    font-weight: bold;
    color: #000;
  }

  .telepon {
    position: absolute;
    bottom: 12mm;
    left: 4mm;
    font-size: 8pt;
    color: #333;
  }
  .barcode {
    position: absolute;
    bottom: 4mm;
    right: 4mm;
    width: 10mm;
    height: 10mm;
    background: #fff;
    padding: 0.mm;
    box-sizing: border-box;
  }
  .barcode img {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }
  .text-left   { text-align: left; }
  .text-center { text-align: center; }
  .text-right  { text-align: right; }
</style>
    </style>
</head>
<body>
    <section style="">
        <table width="1%">
            @foreach ($datamember as $key => $data)
                <tr>
                    @foreach ($data as $item)
                        <td class="text-center">
                            <div class="box">
                                <img src="{{ public_path($setting->path_kartu_member) }}" alt="card" width="0%">
                                <div class="logo">
                                    <p>{{ $setting->nama_perusahaan }}</p>
                                    <img src="{{ public_path($setting->path_logo) }}" alt="logo">
                                </div>
                                <div class="nama">{{ $item->nama }}</div>
                                <div class="telepon">{{ $item->telepon }}</div>
                                <div class="barcode text-left">
                                    <img src="data:image/png;base64, {{ DNS2D::getBarcodePNG("$item->kode_member", 'QRCODE') }}" alt="qrcode"
                                        height="45"
                                        widht="45">
                                </div>
                            </div>
                        </td>
                        
                        @if (count($datamember) == 1)
                        <td class="text-ceter" style=" 5;"></td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </table>
    </section>
</body>
</html>