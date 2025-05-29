<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Midtrans\Config;
use Midtrans\Snap;

class Denda extends Model
{
    use HasFactory;

    protected $fillable = ['peminjam_id', 'jumlah_denda', 'keterangan', 'status', 'snap_token'];

    public function peminjam()
    {
        return $this->belongsTo(Peminjam::class);
    }

    public function history()
    {
        return $this->hasMany(HistoryDenda::class);
    }

    protected static function booted()
    {
        static::creating(function ($denda) {
            $denda->status = 'belum_dibayar';

            $peminjam = Peminjam::find($denda->peminjam_id);

            // Load config midtrans
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');

            $params = [
                'transaction_details' => [
                    'order_id' => uniqid('denda-'),
                    'gross_amount' => $denda->jumlah_denda,
                ],
                'customer_details' => [
                    'first_name' => $peminjam->name,
                    'email' => $peminjam->email,
                ],
            ];

            $snapToken = Snap::getSnapToken($params);
            $denda->snap_token = $snapToken;
        });
    }
}
