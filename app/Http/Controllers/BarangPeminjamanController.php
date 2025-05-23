<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangPeminjaman;

class BarangPeminjamanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'peminjam_id' => 'required|exists:peminjams,id',
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_pengembalian' => 'nullable|date|after_or_equal:tanggal_pinjam',
        ]);

        $peminjaman = BarangPeminjaman::create([
            'peminjam_id' => $request->peminjam_id,
            'barang_id' => $request->barang_id,
            'kbarang_id' => $request->kbarang_id,
            'jumlah' => $request->jumlah,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_pengembalian' => $request->tanggal_pengembalian,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'data' => $peminjaman,
        ]);
    }

    public function index()
    {
        return BarangPeminjaman::with(['peminjam', 'barang'])->get();
    }
}