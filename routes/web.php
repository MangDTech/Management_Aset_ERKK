<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/admin/login');

// Route::middleware(['auth', 'role:admin'])->group(function () {
//     Route::get('/admin-dashboard', [AdminController::class, 'showDashboard']);
// });


Route::middleware(['auth', 'role:pimpinan'])->group(function () {
    Route::get('/pimpinan-dashboard', [PimpinanController::class, 'index']);
});

Route::middleware(['auth', 'role:peminjam'])->group(function () {
    Route::get('/peminjam-dashboard', [PeminjamController::class, 'index']);
});

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');  // Menampilkan form register
Route::post('/register', [RegisteredUserController::class, 'store']); 

