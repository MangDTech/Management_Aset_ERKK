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

        if ($request->jumlah > $barang->jumlah_barang) {
            return response()->json([
                'message' => 'Jumlah barang yang diminta melebihi stok tersedia.',
            ], 422);
        }

        // Kurangi stok barang
        $barang->jumlah_barang -= $request->jumlah;
        $barang->save();

        $maintenance = BarangMaintenance::create([
            'barang_id' => $request->barang_id,
            'deskripsi' => $request->deskripsi,
            'jumlah' => $request->jumlah,
            'tanggal_mulai' => $request->tanggal_mulai,
            'status' => 'proses',
        ]);

        return response()->json([
            'message' => 'Data maintenance berhasil disimpan.',
            'data' => $maintenance,
        ], 201);
    }

    public function selesai($id)
    {
        $maintenance = BarangMaintenance::findOrFail($id);

        if ($maintenance->status === 'selesai') {
            return response()->json([
                'message' => 'Maintenance sudah selesai sebelumnya.',
            ], 400);
        }

        $maintenance->status = 'selesai';
        $maintenance->tanggal_selesai = now();
        $maintenance->save();

        // Tambahkan kembali jumlah ke stok barang
        $barang = $maintenance->barang;
        $barang->jumlah_barang += $maintenance->jumlah;
        $barang->save();

        return response()->json([
            'message' => 'Maintenance selesai. Stok barang diperbarui.',
            'data' => $maintenance,
        ]);
    }
}
