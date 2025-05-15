<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Menampilkan halaman pendaftaran.
     */
    public function create()
    {
        return view('auth.register');  // Ganti dengan view yang sesuai
    }

    /**
     * Menyimpan pengguna baru ke dalam database.
     */
    public function store(Request $request)
    {
        // Validasi inputan dari form pendaftaran
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Membuat user baru dengan role default
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Mengenskripsi password
            'role' => 'peminjam',  // Menambahkan role default
        ]);

        // Login otomatis setelah pendaftaran
        Auth::login($user);

        // Redirect ke halaman utama setelah berhasil
        return redirect()->route('home');  // Ganti dengan route tujuan Anda
    }
}
