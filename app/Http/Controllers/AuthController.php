<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Login sebagai Atasan
        if (Auth::guard('atasan')->attempt($request->only('email', 'password'))) {
            $user = Auth::guard('atasan')->user();
            
            return response()->json([
                'success' => true,
                'message' => 'Login berhasil sebagai Atasan.',
                'role' => 'atasan',
                'user' => $user,
            ]);
        }

        // Login sebagai Peminjam
        if (Auth::guard('peminjam')->attempt($request->only('email', 'password'))) {
            $user = Auth::guard('peminjam')->user();

            // Periksa apakah akun sudah diverifikasi
            if (!$user->is_verified) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda belum diverifikasi. Silakan verifikasi email Anda dengan kode OTP.',
                ], 403);
            }

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil sebagai Peminjam.',
                'role' => 'peminjam',
                'user' => $user,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email atau password salah.',
        ], 401);
    }
}
