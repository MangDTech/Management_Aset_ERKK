<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeminjamAuthController;
use App\Models\Peminjam;
use App\Models\Atasan;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OtpRegisterController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register-peminjam', [PeminjamAuthController::class, 'register']);

// ini login untuk atasan dan peminjam

Route::post('/login', [AuthController::class, 'login']);

Route::post('/register', [OtpRegisterController::class, 'register']);
Route::post('/verify-otp', [OtpRegisterController::class, 'verifyOtp']);