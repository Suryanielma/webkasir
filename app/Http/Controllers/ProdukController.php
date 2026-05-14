<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with('kategori');
        if ($request->kategori) $query->where('id_kategori', $request->kategori);
        if ($request->status)   $query->where('status', $request->status);
        if ($request->search)   $query->where('nama_produk', 'like', '%'.$request->search.'%');
        $semua_produk = $query->get();
        return view('produk.index', compact('semua_produk'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('produk.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kategori' => 'required',
            'nama_produk' => 'required|max:255',
            'harga'       => 'required|numeric',
            'status'      => 'required|in:tersedia,habis',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['id_kategori', 'nama_produk', 'harga', 'status']);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        Produk::create($data);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambah!');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $produk   = Produk::findOrFail($id);
        $kategori = Kategori::all();
        return view('produk.edit', compact('produk', 'kategori'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'id_kategori' => 'required',
            'nama_produk' => 'required|max:255',
            'harga'       => 'required|numeric',
            'status'      => 'required|in:tersedia,habis',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $produk = Produk::findOrFail($id);
        $data   = $request->only(['id_kategori', 'nama_produk', 'harga', 'status']);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama kalau ada
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        $produk->update($data);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy(string $id)
    {
        $produk = Produk::findOrFail($id);

        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }

        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus!');
    }

    public function toggleStatus(string $id)
{
    $produk = Produk::findOrFail($id);
    $produk->status = $produk->status === 'tersedia' ? 'habis' : 'tersedia';
    $produk->save();

    return response()->json([
        'status' => $produk->status,
    ]);
}
}