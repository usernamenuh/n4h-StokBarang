<?php


namespace App\Http\Controllers;

use App\Models\rooms;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = rooms::all();
        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:single,double,suite',
            'stock' => 'required|integer|min:0',
            'price' => 'required|integer|min:0',
        ]);

        rooms::create($request->all());
        return redirect()->route('rooms.index')->with('success', 'Data kamar berhasil ditambahkan');
    }

    public function edit($id)
    {
        $room = rooms::findOrFail($id);
        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:single,double,suite',
            'stock' => 'required|integer|min:0',
            'price' => 'required|integer|min:0',
        ]);

        $room = rooms::findOrFail($id);
        $room->update($request->all());
        return redirect()->route('rooms.index')->with('success', 'Data kamar berhasil diupdate');
    }

    public function destroy($id)
    {
        $room = rooms::findOrFail($id);
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Data kamar berhasil dihapus');
    }
}