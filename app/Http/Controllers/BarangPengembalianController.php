<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangPengembalian;
use App\Models\Barang;

class BarangPengembalianController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'peminjam_id' => 'required|exists:peminjams,id',
            'barang_id' => 'required|exists:barangs,id',
            'kbarang_id' => 'required|exists:kbarangs,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_pengembalian' => 'required|date|after_or_equal:tanggal_pinjam',
            'status' => 'required|string',
        ]);

        // Cari data barang yang dikembalikan
        $barang = Barang::find($request->barang_id);

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan.',
            ], 404);
        }

        // Tambahkan jumlah barang ke stok
        $barang->jumlah_barang += $request->jumlah;
        $barang->save();

        // Simpan data pengembalian
        $pengembalian = BarangPengembalian::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil dikembalikan dan stok diperbarui.',
            'data' => $pengembalian,
        ]);
    }

    public function index()
    {
        return BarangPengembalian::with(['peminjam', 'barang', 'kbarang'])->get();
    }
}
