<table>
    <tr>
        <td colspan="7" style="font-size:14px; font-weight:600; text-align:center; background-color:#653361; color:white; padding:15px; letter-spacing:2px;">
            Hasil Analisis ABC
        </td>
    </tr>
    <tr>
        <th style="background:#f2f2f2; font-weight:500;">No</th>
        <th style="background:#f2f2f2; font-weight:500;">Nama Barang</th>
        <th style="background:#f2f2f2; font-weight:500;">Total Qty</th>
        <th style="background:#f2f2f2; font-weight:500;">Total Nilai (Rp)</th>
        <th style="background:#f2f2f2; font-weight:500;">Persentase (%)</th>
        <th style="background:#f2f2f2; font-weight:500;">Kategori</th>
        <th style="background:#f2f2f2; font-weight:500;">Stok Saat Ini</th>
    </tr>

    @foreach($analisis as $index => $item)
        <tr>
            <td style="text-align:center; padding:8px; border-bottom:6px solid #fff;">{{ $index + 1 }}</td>
            <td style="padding:8px; border-bottom:6px solid #fff;">{{ $item->nama_barang }}</td>
            <td style="text-align:center; padding:8px; border-bottom:6px solid #fff;">{{ number_format($item->total_qty, 0, ',', '.') }}</td>
            <td style="text-align:center; padding:8px; border-bottom:6px solid #fff;">Rp {{ number_format($item->total_nilai, 0, ',', '.') }}</td>
            <td style="text-align:center; padding:8px; border-bottom:6px solid #fff;">{{ $item->persentase }}%</td>
            <td style="text-align:center; padding:8px; border-bottom:6px solid #fff;">{{ $item->kategori }}</td>
            <td style="text-align:center; padding:8px; border-bottom:6px solid #fff;">{{ number_format($item->stok_saat_ini, 0, ',', '.') }}</td>
        </tr>
    @endforeach
</table>
