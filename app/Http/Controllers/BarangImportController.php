<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BarangImportController extends Controller
{
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
        $importer = new \App\Imports\BarangImportFinal;
        $importer->import($request->file('file'));

        return redirect()->back()->with([
            'success' => 'Import barang berhasil!',
            'import_errors' => $importer->getErrors()
        ]);
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['file' => 'Gagal import: ' . $e->getMessage()]);
    }
}

public function downloadTemplate()
{
    return response()->download(storage_path('app/templates/template_barang.xlsx'));
}

}
