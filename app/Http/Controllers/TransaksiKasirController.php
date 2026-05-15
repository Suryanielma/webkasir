<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Produk;

class TransaksiKasirController extends Controller
{
    public function index(Request $request)
    {
        $kategoris = Kategori::all();
        
        $query = Produk::with('kategori');
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }
        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }
        $query->where('status', 'Tersedia'); // Asumsikan hanya yang tersedia
        $produk = $query->get();

        return view('transaksi', compact('kategoris', 'produk'));
    }
}
