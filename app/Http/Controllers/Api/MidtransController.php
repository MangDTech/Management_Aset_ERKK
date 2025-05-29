<?php

namespace App\Http\Controllers\Api;

use App\Models\Denda;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Midtrans\Config;
use App\Models\HistoryDenda;

class MidtransController extends Controller
{
    protected static $midtransInitialized = false;

    public function __construct()
    {
        if (!self::$midtransInitialized) {
            $this->initializeMidtrans();
            self::$midtransInitialized = true;
        }
    }

    protected function initializeMidtrans()
    {
        try {
            $serverKey = env('MIDTRANS_SERVER_KEY');
            if (empty($serverKey)) {
                throw new \Exception('Midtrans server key is not set in .env');
            }

            Config::$serverKey = $serverKey;
            Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            Config::$isSanitized = true;
            Config::$is3ds = true;
            Config::$curlOptions = [
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0
            ];

            \Log::debug('Midtrans Config Loaded', [
                'serverKey' => substr($serverKey, 0, 6) . '...' . substr($serverKey, -4),
                'isProduction' => Config::$isProduction
            ]);
        } catch (\Exception $e) {
            \Log::critical('Midtrans Config Failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function handle(Request $request)
    {
        \Log::info('Midtrans Notification Received', ['ip' => $request->ip()]);

        try {
            $payload = $request->all();

            \Log::debug('Notification Payload', [
                'order_id' => $payload['order_id'] ?? null,
                'status' => $payload['transaction_status'] ?? null,
                'type' => $payload['payment_type'] ?? null
            ]);

            // Validasi minimal
            if (!isset($payload['transaction_status']) || !isset($payload['order_id'])) {
                throw new \Exception('Invalid notification payload');
            }

            // Proses order DND
            if (strpos($payload['order_id'], 'DND-') === 0) {
                return $this->updateDendaStatus(
                    $payload['order_id'],
                    $payload['transaction_status'],
                    $payload['fraud_status'] ?? null,
                    $payload['payment_type'] ?? null
                );
            }

            return $this->sendResponse('Notification received but no action taken');

        } catch (\Exception $e) {
            \Log::error('Notification Error', [
                'error' => $e->getMessage(),
                'payload' => $request->all()
            ]);

            return $this->sendError($e->getMessage(), 500);
        }
    }

    protected function validateSignatureKey(array $payload)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $expectedSignature = hash('sha512',
            $payload['order_id'] .
            $payload['status_code'] .
            $payload['gross_amount'] .
            $serverKey
        );

        if (!isset($payload['signature_key']) || $payload['signature_key'] !== $expectedSignature) {
            throw new \Exception('Invalid signature key.');
        }

        \Log::debug('Signature Key Validated', [
            'order_id' => $payload['order_id']
        ]);
    }

    protected function updateDendaStatus($orderId, $status, $fraudStatus, $paymentType)
    {
        try {
            $dendaId = explode('-', $orderId)[1] ?? null;

            if (!$dendaId || !is_numeric($dendaId)) {
                throw new \Exception("Invalid Denda ID in order_id: {$orderId}");
            }

            $denda = Denda::findOrFail($dendaId);
            $originalStatus = $denda->status;

            $newStatus = match ($status) {
                'capture' => ($paymentType === 'credit_card' && $fraudStatus !== 'accept') ? 'pending' : 'lunas',
                'settlement' => 'lunas',
                'pending' => 'pending',
                'deny', 'cancel', 'expire' => 'gagal',
                default => $originalStatus
            };

            if ($newStatus !== $originalStatus) {
                $denda->status = $newStatus;
                $denda->save();

                \Log::info('Denda Status Updated', [
                    'denda_id' => $denda->id,
                    'old_status' => $originalStatus,
                    'new_status' => $newStatus
                ]);

                // ⬇️ Tambahan: jika lunas, simpan ke history_dendas
                if ($newStatus === 'lunas') {
                    HistoryDenda::create([
                        'denda_id' => $denda->id,
                        'status' => $newStatus,
                        'payment_type' => $paymentType,
                        'order_id' => $orderId,
                    ]);

                    \Log::info('History Denda Saved', [
                        'denda_id' => $denda->id,
                        'order_id' => $orderId
                    ]);
                }
            }

            return $this->sendResponse('Denda status updated', [
                'denda_id' => $denda->id,
                'status' => $newStatus
            ]);
        } catch (\Exception $e) {
            \Log::error('Denda Update Failed', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    protected function sendResponse($message, $data = [])
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'timestamp' => now()->toDateTimeString()
        ], 200, [
            'Content-Type' => 'application/json',
            'Connection' => 'close'
        ]);
    }

    protected function sendError($message, $statusCode)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'timestamp' => now()->toDateTimeString()
        ], $statusCode, [
            'Content-Type' => 'application/json',
            'Connection' => 'close'
        ]);
    }

    // history denda end point api 
    // public function getHistoryDenda($userId)
    // {
    //     try {
    //         // Ambil semua denda yang dimiliki user
    //         $dendas = Denda::where('user_id', $userId)->pluck('id');

    //         // Ambil history berdasarkan denda_id yang dimiliki user
    //         $history = HistoryDenda::whereIn('denda_id', $dendas)
    //             ->orderBy('created_at', 'desc')
    //             ->get();

    //         return $this->sendResponse('History Denda fetched successfully', $history);
    //     } catch (\Exception $e) {
    //         \Log::error('Fetch History Denda Failed', [
    //             'user_id' => $userId,
    //             'error' => $e->getMessage()
    //         ]);

    //         return $this->sendError('Failed to fetch history denda', 500);
    //     }
    // }
}
