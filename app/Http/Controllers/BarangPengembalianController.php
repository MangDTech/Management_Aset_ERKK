<?php

namespace App\Http\Controllers;

use App\Models\BarangPengembalian;
use Illuminate\Http\Request;

class BarangPengembalianController extends Controller

{
    public function store(Request $request)
    {
       $request->validate([
            'peminjam_id' => 'required|exists:peminjams,id',
            'barang_id' => 'required|exists:barangs,id',
            'kbarang_id' => 'required|exists:kbarangs,id',
            'jumlah' => 'required|integer',
            'tanggal_pinjam' => 'required|date',
            'tanggal_pengembalian' => 'required|date',
            'status' => 'required|string',
        ]);

        $pengembalian = BarangPengembalian::create($request->all());
        return response()->json(['success' => true, 'data' => $pengembalian]);
    }

    public function index()
    {
        return BarangPengembalian::with(['peminjam', 'barang', 'kbarang'])->get();
    }
}
