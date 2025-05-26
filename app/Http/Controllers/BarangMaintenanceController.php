<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMaintenance;
use Illuminate\Http\Request;

class BarangMaintenanceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'deskripsi' => 'required|string',
            'jumlah' => 'required|integer|min:1',
            'tanggal_mulai' => 'required|date',
        ]);

        $barang = Barang::find($request->barang_id);

        // Validasi stok cukup
        if ($request->jumlah > $barang->jumlah_barang) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah melebihi stok. Stok tersedia: ' . $barang->jumlah_barang
            ], 400);
        }

        // Buat maintenance
        $maintenance = BarangMaintenance::create([
            'barang_id' => $request->barang_id,
            'deskripsi' => $request->deskripsi,
            'jumlah' => $request->jumlah,
            'tanggal_mulai' => $request->tanggal_mulai,
            'status' => 'proses' // PASTIKAN status langsung 'proses'
        ]);

        // Kurangi stok barang
        $barang->jumlah_barang -= $request->jumlah;
        $barang->save();

        return response()->json([
            'success' => true,
            'message' => 'Maintenance berhasil dibuat',
            'data' => $maintenance,
            'sisa_stok' => $barang->jumlah_barang
        ], 201);
    }

    public function selesai($id)
    {
        $maintenance = BarangMaintenance::with('barang')->findOrFail($id);

        // Validasi status
        if ($maintenance->status === 'selesai') {
            return response()->json([
                'success' => false,
                'message' => 'Maintenance sudah selesai sebelumnya'
            ], 400);
        }

        // Tambah stok barang
        $maintenance->barang->jumlah_barang += $maintenance->jumlah;
        $maintenance->barang->save();

        // Update maintenance
        $maintenance->update([
            'status' => 'selesai',
            'tanggal_selesai' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Maintenance selesai. Stok dikembalikan',
            'data' => $maintenance,
            'stok_sekarang' => $maintenance->barang->jumlah_barang
        ]);
    }

    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => BarangMaintenance::with('barang')->get()
        ]);
    }
}