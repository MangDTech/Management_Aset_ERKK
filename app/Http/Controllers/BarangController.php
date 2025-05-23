<?php

namespace App\Http\Controllers;

use App\Models\Barang;

class BarangController extends Controller
{
    public function index()
    {
        // Pastikan relasi 'category' dan 'kbarang' sudah ada di model Barang
        return Barang::with(['category', 'kbarang'])->get();
    }
}