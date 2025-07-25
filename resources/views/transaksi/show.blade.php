@extends('layouts.demo')

@section('title', 'Detail Transaksi')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Detail Transaksi: {{ $transaksi->nomor }}</h1>

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><strong>Tanggal:</strong> {{ $transaksi->tanggal }}</div>
            <div><strong>Nomor:</strong> {{ $transaksi->nomor }}</div>
            <div><strong>Customer:</strong> {{ $transaksi->customer }}</div>
            <div><strong>Subtotal:</strong> Rp {{ number_format($transaksi->subtotal, 2, ',', '.') }}</div>
            <div><strong>Diskon:</strong> Rp {{ number_format($transaksi->disc, 2, ',', '.') }}</div>
            <div><strong>Ongkos Kirim:</strong> Rp {{ number_format($transaksi->ongkos_kirim, 2, ',', '.') }}</div>
            <div><strong>Total:</strong> Rp {{ number_format($transaksi->total, 2, ',', '.') }}</div>
            <div><strong>Keterangan:</strong> {{ $transaksi->keterangan }}</div>
            <div><strong>Tanggal Input:</strong> {{ $transaksi->tgl_input }}</div>
            <div><strong>User Input:</strong> {{ $transaksi->user->name ?? $transaksi->user_id }}</div>
            <div><strong>Jumlah Print:</strong> {{ $transaksi->jum_print }}</div>
        </div>
    </div>

    <h2 class="text-xl font-bold mb-4">Item Transaksi</h2>
    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Barang
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Kuantitas
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Harga Satuan
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Diskon Item
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Subtotal Item
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Keterangan Item
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transaksi->details as $detail)
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        {{ $detail->barang->nama ?? 'Barang Tidak Ditemukan' }} ({{ $detail->barang->kode ?? 'N/A' }})
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        {{ $detail->quantity }}
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        Rp {{ number_format($detail->price, 2, ',', '.') }}
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        Rp {{ number_format($detail->discount, 2, ',', '.') }}
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        Rp {{ number_format($detail->subtotal, 2, ',', '.') }}
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        {{ $detail->keterangan }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                        Tidak ada item untuk transaksi ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-end">
        <a href="{{ route('transaksi.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">
            Kembali
        </a>
    </div>
</div>
@endsection
