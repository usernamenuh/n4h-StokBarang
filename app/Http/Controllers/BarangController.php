<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{

    public function index(Request $request)
    {
        $query = Barang::with('user');


        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->orWhere('keterangan', 'LIKE', "%{$search}%");
            });
        }

        // Filter by golongan
        if ($request->filled('golongan')) {
            $query->where('golongan', $request->golongan);
        }

        // Filter by user
        if ($request->filled('user_filter')) {
            $query->where('user_id', $request->user_filter);
        }

        // Order by latest
        $query->orderBy('created_at', 'desc');

        // Get all barangs for DataTables (no pagination)
        $barangs = $query->get();

        return view('barang.index', compact('barangs',));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('barang.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'does_pcs' => 'required|numeric|min:1',
            'golongan' => 'required|string|max:255',
            'hbeli' => 'required|numeric|min:0',
            'user_id' => 'nullable|exists:users,id',
            'keterangan' => 'nullable|string|max:1000',
        ], [
            'kode.required' => 'Kode barang wajib diisi.',
            'nama.required' => 'Nama barang wajib diisi.',
            'nama.max' => 'Nama barang maksimal 255 karakter.',
            'does_pcs.required' => 'Nilai konversi unit wajib diisi.',
            'does_pcs.numeric' => 'Nilai konversi unit harus berupa angka.',
            'does_pcs.min' => 'Minimal nilai konversi adalah 1.',
            'golongan.required' => 'Golongan/Kategori wajib diisi.',
            'golongan.max' => 'Golongan maksimal 255 karakter.',
            'hbeli.required' => 'Harga beli wajib diisi.',
            'hbeli.numeric' => 'Harga beli harus berupa angka.',
            'hbeli.min' => 'Harga beli tidak boleh negatif.',
            'user_id.exists' => 'User yang dipilih tidak valid.',
            'keterangan.max' => 'Keterangan maksimal 1000 karakter.',
        ]);



        Barang::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'does_pcs' => $request->does_pcs,
            'golongan' => $request->golongan,
            'hbeli' => $request->hbeli,
            'user_id' => $request->user_id ?? Auth::id(),
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function show(Barang $barang)
    {
        $barang->load('user');
        return view('barang.show', compact('barang'));
    }

    public function edit(Barang $barang)
    {
        $users = User::orderBy('name')->get();
        return view('barang.edit', compact('barang', 'users'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'kode' => 'required|string|max:255', 
            'nama' => 'required|string|max:255',
            'does_pcs' => 'required|numeric|min:1',
            'golongan' => 'required|string|max:255',
            'hbeli' => 'required|numeric|min:0',
            'user_id' => 'nullable|exists:users,id',
            'keterangan' => 'nullable|string|max:1000',
        ], [
            'kode.required' => 'Kode barang wajib diisi.',
            'nama.required' => 'Nama barang wajib diisi.',
            'nama.max' => 'Nama barang maksimal 255 karakter.',
            'does_pcs.required' => 'Nilai konversi unit wajib diisi.',
            'does_pcs.numeric' => 'Nilai konversi unit harus berupa angka.',
            'does_pcs.min' => 'Minimal nilai konversi adalah 1.',
            'golongan.required' => 'Golongan/Kategori wajib diisi.',
            'golongan.max' => 'Golongan maksimal 255 karakter.',
            'hbeli.required' => 'Harga beli wajib diisi.',
            'hbeli.numeric' => 'Harga beli harus berupa angka.',
            'hbeli.min' => 'Harga beli tidak boleh negatif.',
            'user_id.exists' => 'User yang dipilih tidak valid.',
            'keterangan.max' => 'Keterangan maksimal 1000 karakter.',
        ]);
        $barang->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'does_pcs' => $request->does_pcs,
            'golongan' => $request->golongan,
            'hbeli' => $request->hbeli,
            'user_id' => $request->user_id,
            'keterangan' => $request->keterangan,
        ]);
        return redirect()->route('barang.show', $barang->id)->with('success', 'Barang berhasil diupdate!');
    }
    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus!');
    }
    public function showImportForm()
    {
        return view('barang.import');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            // Simulate processing time
            sleep(2);
            $importer = new \App\Imports\BarangImportFinal;
            $result = $importer->import($request->file('file'));

            // Get detailed results from importer
            $importResults = [
                'total_data' => $importer->getTotalData(),
                'berhasil' => $importer->getJumlahBerhasil(),
                'user_dibuat' => $importer->getJumlahUserDibuat(),
                'gagal' => count($importer->getDaftarError()),
                'errors' => $importer->getDaftarError(),
                'baris_gagal' => $importer->getBarisGagal(),
                'baris_berhasil' => $importer->getBarisBerhasil()
            ];
            return response()->json([
                'success' => true,
                'message' => 'Import selesai diproses!',
                'data' => $importResults
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal import: ' . $e->getMessage(),
                'data' => [
                    'total_data' => 0,
                    'berhasil' => 0,
                    'user_dibuat' => 0,
                    'gagal' => 1,
                    'errors' => [$e->getMessage()],
                    'baris_gagal' => [],
                    'baris_berhasil' => []
                ]
            ], 422);
        }
    }
}