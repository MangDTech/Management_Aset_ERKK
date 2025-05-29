<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HistoryDenda;
use Illuminate\Http\Request;

class HistoryDendaController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Jika ingin berdasarkan user_id (opsional)
            $userId = $request->query('user_id');

            // Ambil semua data atau filter berdasarkan user_id (jika ada)
            $query = HistoryDenda::query();

            if ($userId) {
                // Asumsikan HistoryDenda -> denda -> user_id
                $query->whereHas('denda', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                });
            }

            $data = $query->with('denda')->get(); // Relasi denda untuk detail tambahan

            return response()->json([
                'status' => 'success',
                'message' => 'Data history denda berhasil diambil',
                'data' => $data,
                'timestamp' => now()->toDateTimeString()
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Get History Denda Failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data history denda',
                'timestamp' => now()->toDateTimeString()
            ], 500);
        }
    }
}
