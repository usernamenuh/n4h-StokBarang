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
        $role = auth()->user()->role;

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

        return view('barang.index', compact('barangs', 'role'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('barang.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:barangs,kode',
            'nama' => 'required',
            'does_pcs' => 'required|numeric|min:0',
            'golongan' => 'required',
            'hbeli' => 'required|numeric|min:0',
            'user_id' => 'nullable|exists:users,id',
            'keterangan' => 'nullable|string'
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
            'kode' => [
                'required',
                Rule::unique('barangs')->where(function ($query) use ($request) {
                    return $query->where('nama', $request->nama);
                })->ignore($barang->id),
            ],
            'nama' => 'required',
            'does_pcs' => 'required|numeric|min:0',
            'golongan' => 'required',
            'hbeli' => 'required|numeric|min:0',
            'user_id' => 'nullable|exists:users,id',
            'keterangan' => 'nullable|string'
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
    $importer->import($request->file('file'));
            
            return response()->json([
                'success' => true,
                'message' => 'Import berhasil! Data barang telah ditambahkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal import: ' . $e->getMessage()
            ], 422);
        }
    }

   
}
