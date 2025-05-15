<?php

namespace App\Http\Controllers;

use App\Models\Peminjam;
use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OtpRegisterController extends Controller
{
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:peminjams,email',
            'password' => 'required|string|min:6',
        ]);

        // Simpan data peminjam
        $peminjam = Peminjam::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // Akan otomatis di-hash oleh mutator
        ]);

        // Generate OTP
        $otpCode = Str::random(6); // Kode OTP 6 karakter
        $expiresAt = Carbon::now()->addMinutes(10); // OTP berlaku selama 10 menit

        // Simpan OTP ke database
        Otp::create([
            'peminjam_id' => $peminjam->id,
            'otp_code' => $otpCode,
            'expires_at' => $expiresAt,
        ]);

        // Kirim OTP ke email
        Mail::raw("Hallo Mohon Untuk tidak di balas pesan dari MA-ERKK System ,Kode OTP Anda adalah: $otpCode", function ($message) use ($peminjam) {
            $message->to($peminjam->email)
                    ->subject('Kode OTP Anda');
        });

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil. Kode OTP telah dikirim ke email Anda.',
        ]);
    }

    public function verifyOtp(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required|string',
        ]);

        // Cari peminjam berdasarkan email
        $peminjam = Peminjam::where('email', $request->email)->first();

        if (!$peminjam) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan.',
            ], 404);
        }

        // Cari OTP yang sesuai
        $otp = Otp::where('peminjam_id', $peminjam->id)
                  ->where('otp_code', $request->otp_code)
                  ->where('expires_at', '>', Carbon::now())
                  ->first();

        if (!$otp) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP tidak valid atau telah kedaluwarsa.',
            ], 400);
        }

        // Hapus OTP setelah berhasil diverifikasi
        $otp->delete();

        $peminjam->update([
            'is_verified' => true, // Tandai peminjam sebagai terverifikasi
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kode OTP berhasil diverifikasi.',
        ]);
    }
}