<?php

namespace App\Http\Controllers\Api;

use App\Models\Denda;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function handle(Request $request)
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        try {
            $notification = new Notification();
            
            $transaction = $notification->transaction_status;
            $orderId = $notification->order_id;
            $fraud = $notification->fraud_status;

            // Ambil ID denda dari order_id (format: DND-{id}-{timestamp})
            $parts = explode('-', $orderId);
            $dendaId = $parts[1] ?? null;

            if (!$dendaId) {
                return response()->json(['message' => 'Invalid order ID'], 400);
            }

            $denda = Denda::find($dendaId);

            if (!$denda) {
                return response()->json(['message' => 'Denda tidak ditemukan'], 404);
            }

            // Handle status transaksi
            if ($transaction == 'capture') {
                if ($fraud == 'challenge') {
                    $denda->update(['status' => 'pending']);
                } else if ($fraud == 'accept') {
                    $denda->update(['status' => 'lunas']);
                }
            } else if ($transaction == 'settlement') {
                $denda->update(['status' => 'lunas']);
            } else if ($transaction == 'pending') {
                $denda->update(['status' => 'pending']);
            } else if ($transaction == 'deny' || $transaction == 'cancel' || $transaction == 'expire') {
                $denda->update(['status' => 'gagal']);
            }

            return response()->json(['message' => 'Notifikasi diproses'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error processing notification: ' . $e->getMessage()], 500);
        }
    }
}