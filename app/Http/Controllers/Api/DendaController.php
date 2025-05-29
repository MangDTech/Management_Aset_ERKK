<?php
namespace App\Http\Controllers\Api;

use App\Models\Denda;
use App\Models\Peminjam;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Facades\Log;

class DendaController extends Controller
{
    // Inisialisasi konfigurasi Midtrans
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function store(Request $request)
    {
        $request->validate([
            'peminjam_id' => 'required|exists:peminjams,id',
            'jumlah_denda' => 'required|numeric|min:1000',
            'keterangan' => 'required|string|max:255'
        ]);

        try {
            // Buat denda
            $denda = Denda::create([
                'peminjam_id' => $request->peminjam_id,
                'jumlah_denda' => $request->jumlah_denda,
                'keterangan' => $request->keterangan,
                'status' => 'belum_dibayar'
            ]);

            $peminjam = Peminjam::findOrFail($request->peminjam_id);

            // Persiapkan parameter transaksi
            $params = [
                'transaction_details' => [
                    'order_id' => 'DND-' . $denda->id . '-' . time(),
                    'gross_amount' => $denda->jumlah_denda,
                ],
                'customer_details' => [
                    'first_name' => $peminjam->name,
                    'email' => $peminjam->email ?? 'no-email@example.com',
                    'phone' => $peminjam->phone ?? '08123456789',
                ],
                'item_details' => [
                    [
                        'id' => 'DND-' . $denda->id,
                        'price' => $denda->jumlah_denda,
                        'quantity' => 1,
                        'name' => 'Denda: ' . $denda->keterangan,
                    ]
                ]
            ];

            // Generate Snap Token
            $snapToken = Snap::getSnapToken($params);
            
            // Update denda dengan snap_token
            $denda->update(['snap_token' => $snapToken]);

            return response()->json([
                'success' => true,
                'message' => 'Denda berhasil dibuat',
                'data' => $denda
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error creating denda: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat denda',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUserDenda($userId)
{
    try {
        $dendas = Denda::where('peminjam_id', $userId)
                    ->where('status', 'belum_dibayar')
                    ->get();

        // Generate token untuk setiap denda yang belum punya token valid
        foreach ($dendas as $denda) {
            if (empty($denda->snap_token) || strlen($denda->snap_token) < 50) {
                $this->generateValidSnapToken($denda);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $dendas
        ]);

    } catch (\Exception $e) {
        Log::error('Error getting denda: '.$e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengambil data denda'
        ], 500);
    }
}

protected function generateValidSnapToken(Denda $denda)
{
    // Pastikan konfigurasi Midtrans sudah benar
    Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    Config::$isProduction = false; // Sandbox mode
    Config::$isSanitized = true;
    Config::$is3ds = true;

    $peminjam = $denda->peminjam;

    $params = [
        'transaction_details' => [
            'order_id' => 'DND-'.$denda->id.'-'.time(), // Format: DND-1-123456789
            'gross_amount' => $denda->jumlah_denda,
        ],
        'customer_details' => [
            'first_name' => $peminjam->name,
            'email' => $peminjam->email ?? 'no-email@example.com',
            'phone' => $peminjam->phone ?? '08123456789',
        ],
        'item_details' => [
            [
                'id' => 'DND-'.$denda->id,
                'name' => 'Denda: '.$denda->keterangan,
                'price' => $denda->jumlah_denda,
                'quantity' => 1,
            ]
        ]
    ];

    try {
        $snapToken = Snap::getSnapToken($params);
        $denda->update(['snap_token' => $snapToken]);
        return $snapToken;
    } catch (\Exception $e) {
        Log::error('Gagal generate Snap Token: '.$e->getMessage());
        return null;
    }
}

    // Endpoint untuk handle notifikasi dari Midtrans
    // public function handleNotification(Request $request)
    // {
    //     try {
    //         $notif = new \Midtrans\Notification();
            
    //         $transaction = $notif->transaction_status;
    //         $type = $notif->payment_type;
    //         $orderId = $notif->order_id;
    //         $fraud = $notif->fraud_status;

    //         // Extract denda ID dari order_id (DND-1-123456)
    //         $dendaId = explode('-', $orderId)[1];
    //         $denda = Denda::findOrFail($dendaId);

    //         if ($transaction == 'capture') {
    //             if ($type == 'credit_card') {
    //                 if ($fraud == 'challenge') {
    //                     $denda->status = 'pending';
    //                 } else {
    //                     $denda->status = 'lunas';
    //                 }
    //             }
    //         } elseif ($transaction == 'settlement') {
    //             $denda->status = 'lunas';
    //         } elseif ($transaction == 'pending') {
    //             $denda->status = 'pending';
    //         } elseif ($transaction == 'deny' || 
    //                  $transaction == 'expire' || 
    //                  $transaction == 'cancel') {
    //             $denda->status = 'gagal';
    //         }

    //         $denda->save();

    //         return response()->json(['status' => 'success']);

    //     } catch (\Exception $e) {
    //         Log::error('Notification handler error: ' . $e->getMessage());
    //         return response()->json(['status' => 'error'], 500);
    //     }
    // }
}