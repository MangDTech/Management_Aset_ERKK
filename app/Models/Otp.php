<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = [
        'peminjam_id',
        'otp_code',
        'expires_at',
    ];
    public function peminjam()
    {
        return $this->belongsTo(Peminjam::class);
    }
}
