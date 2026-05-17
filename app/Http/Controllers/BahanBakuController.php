<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\DetailBahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BahanBakuController extends Controller
{
    /**
     * Daftar semua belanja bahan baku.
     */
    public function index(Request $request)
    {
        $query = BahanBaku::latest('tgl_pembelian');

        if ($request->filled('search')) {
            $query->where('keterangan', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tgl_pembelian', $request->tanggal);
        }

        $bahanBakus = $query->paginate(10)->withQueryString();

        return view('BahanBaku.bahan-baku', compact('bahanBakus'));
    }

    /**
     * Form tambah belanja baru.
     */
    public function create()
    {
        return view('BahanBaku.create');
    }

    /**
     * Simpan belanja baru beserta detail item-nya.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tgl_pembelian'    => 'required|date',
            'keterangan'       => 'required|string|max:255',
            'metode_pembayaran'=> 'required|in:Tunai,Transfer,QRIS',
            'items'            => 'required|array|min:1',
            'items.*.nama_bahan'  => 'required|string|max:255',
            'items.*.qty'         => 'required|numeric|min:0.01',
            'items.*.satuan'      => 'required|string|max:20',
            'items.*.harga_total' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $bahanBaku = BahanBaku::create([
                'tgl_pembelian'     => $request->tgl_pembelian,
                'keterangan'        => $request->keterangan,
                'metode_pembayaran' => $request->metode_pembayaran,
                'total_pengeluaran' => 0,
            ]);

            foreach ($request->items as $item) {
                $bahanBaku->details()->create([
                    'nama_bahan'  => $item['nama_bahan'],
                    'qty'         => $item['qty'],
                    'satuan'      => $item['satuan'],
                    'harga_total' => $item['harga_total'],
                ]);
            }

            $bahanBaku->recalculateTotal();
        });

        return redirect()->route('bahan-baku.index')
            ->with('success', 'Data belanja berhasil disimpan');
    }

    /**
     * Detail satu transaksi belanja.
     */
    public function show(BahanBaku $bahanBaku)
    {
        $bahanBaku->load('details');
        return view('BahanBaku.show', compact('bahanBaku'));
    }

    /**
     * Form edit transaksi belanja.
     */
    public function edit(BahanBaku $bahanBaku)
    {
        $bahanBaku->load('details');
        return view('BahanBaku.edit', compact('bahanBaku'));
    }

    /**
     * Update transaksi belanja beserta detail item-nya.
     */
    public function update(Request $request, BahanBaku $bahanBaku)
    {
        $request->validate([
            'tgl_pembelian'    => 'required|date',
            'keterangan'       => 'required|string|max:255',
            'metode_pembayaran'=> 'required|in:Tunai,Transfer,QRIS',
            'items'            => 'required|array|min:1',
            'items.*.nama_bahan'  => 'required|string|max:255',
            'items.*.qty'         => 'required|numeric|min:0.01',
            'items.*.satuan'      => 'required|string|max:20',
            'items.*.harga_total' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $bahanBaku) {
            $bahanBaku->update([
                'tgl_pembelian'     => $request->tgl_pembelian,
                'keterangan'        => $request->keterangan,
                'metode_pembayaran' => $request->metode_pembayaran,
            ]);

            // Hapus detail lama, ganti dengan yang baru
            $bahanBaku->details()->delete();

            foreach ($request->items as $item) {
                $bahanBaku->details()->create([
                    'nama_bahan'  => $item['nama_bahan'],
                    'qty'         => $item['qty'],
                    'satuan'      => $item['satuan'],
                    'harga_total' => $item['harga_total'],
                ]);
            }

            $bahanBaku->recalculateTotal();
        });

        return redirect()->route('bahan-baku.index')
            ->with('success', 'Data belanja berhasil diperbarui.');
    }

    /**
     * Hapus transaksi belanja (detail terhapus otomatis via cascade).
     */
    public function destroy(BahanBaku $bahanBaku)
    {
        $bahanBaku->delete();

        return redirect()->route('bahan-baku.index')
            ->with('success', 'Data belanja berhasil dihapus.');
    }

    /**
     * Form edit item detail.
     */
    public function editDetail(BahanBaku $bahanBaku, DetailBahanBaku $detail)
    {
        if ($detail->bahan_baku_id !== $bahanBaku->id) {
            abort(403, 'Unauthorized');
        }

        return view('BahanBaku.edit-detail', compact('bahanBaku', 'detail'));
    }

    /**
     * Update item detail.
     */
    public function updateDetail(Request $request, BahanBaku $bahanBaku, DetailBahanBaku $detail)
    {
        if ($detail->bahan_baku_id !== $bahanBaku->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'nama_bahan'  => 'required|string|max:255',
            'qty'         => 'required|numeric|min:0.01',
            'satuan'      => 'required|string|max:20',
            'harga_total' => 'required|numeric|min:0',
        ]);

        $detail->update($request->only(['nama_bahan', 'qty', 'satuan', 'harga_total']));
        $bahanBaku->recalculateTotal();

        return redirect()->route('bahan-baku.show', $bahanBaku)
            ->with('success', 'Item belanja berhasil diperbarui.');
    }

    /**
     * Hapus item detail dari belanja.
     */
    public function destroyDetail(BahanBaku $bahanBaku, DetailBahanBaku $detail)
    {
        if ($detail->bahan_baku_id !== $bahanBaku->id) {
            abort(403, 'Unauthorized');
        }

        $detail->delete();
        $bahanBaku->recalculateTotal();

        return redirect()->route('bahan-baku.show', $bahanBaku)
            ->with('success', 'Item belanja berhasil dihapus.');
    }
}