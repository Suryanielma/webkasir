<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk TRX-{{ $transaksi->id_transaksi }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
            margin: 0 auto;
            padding: 10px;
            width: 300px; /* Lebar standar printer thermal 80mm */
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .mt-2 { margin-top: 8px; }
        .mb-2 { margin-bottom: 8px; }
        .border-top { border-top: 1px dashed #000; padding-top: 8px; }
        .border-bottom { border-bottom: 1px dashed #000; padding-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 4px 0; vertical-align: top; }
        .item-name { display: block; margin-bottom: 2px; }
        
        @media print {
            @page { margin: 0; }
            body { margin: 10px; }
        }
    </style>
</head>
<body onload="window.print();">
    <div class="text-center mb-2">
        <h2 style="margin:0; font-size: 16px;">SOTO MBA RATIH</h2>
        <p style="margin:2px 0 0 0;">Jl. karanganyar No. 123</p>
        <p style="margin:0;">Telp: 0812-3456-7890</p>
    </div>

    <div class="border-top mb-2">
        <table style="width: 100%;">
            <tr>
                <td>No</td>
                <td>: TRX-{{ $transaksi->id_transaksi }}</td>
            </tr>
            <tr>
                <td>Waktu</td>
                <td>: {{ \Carbon\Carbon::parse($transaksi->waktu_transaksi)->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td>Kasir</td>
                <td>: {{ $transaksi->nama_kasir ?? ($transaksi->sesiKasir && $transaksi->sesiKasir->user ? $transaksi->sesiKasir->user->username : 'Unknown') }}</td>
            </tr>
            <tr>
                <td>Tipe</td>
                <td>: {{ $transaksi->tipe_pesanan ?? '-' }}</td>
            </tr>
            <tr>
                <td>Pelanggan</td>
                <td>: {{ $transaksi->nama_pembeli ?? '-' }}</td>
            </tr>
            @if($transaksi->nomor_meja)
            <tr>
                <td>No Meja</td>
                <td>: {{ $transaksi->nomor_meja }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="border-top border-bottom mb-2">
        <table>
            @foreach($transaksi->detailTransaksi as $detail)
            <tr>
                <td colspan="3"><span class="item-name">{{ $detail->produk->nama_produk }}</span></td>
            </tr>
            <tr>
                <td style="width: 15%;">{{ $detail->jumlah }}x</td>
                <td style="width: 35%;">{{ number_format($detail->produk->harga, 0, ',', '.') }}</td>
                <td style="width: 50%;" class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </table>
    </div>

    <div class="mb-2">
        <table>
            <tr>
                <td class="font-bold">Total</td>
                <td class="font-bold text-right">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Bayar</td>
                <td class="text-right">Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Kembali</td>
                <td class="text-right">Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="border-top text-center mt-2 pt-2">
        <p style="margin:0;">Terima Kasih Atas Kunjungan Anda</p>
        <p style="margin:0;">-- LUNAS --</p>
    </div>
</body>
</html>
