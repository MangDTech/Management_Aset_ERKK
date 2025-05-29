<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryDenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'denda_id',
        'status',
        'payment_type',
        'order_id',
    ];

    public function denda()
    {
        return $this->belongsTo(Denda::class);
    }
}
