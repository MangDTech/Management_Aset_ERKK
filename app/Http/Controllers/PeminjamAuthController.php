<?php

namespace App\Http\Controllers;

use App\Models\Peminjam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PeminjamAuthController extends Controller
{
    public function register(Request $request)
    {
        // Validasi data
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:peminjams,email',
            'password' => 'required|string|min:6',
        ]);

        // Kalau validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Simpan ke database
        $peminjam = Peminjam::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password, // Akan di-hash otomatis dari model
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Peminjam berhasil didaftarkan',
            'data' => $peminjam
        ], 201);
    }
}
