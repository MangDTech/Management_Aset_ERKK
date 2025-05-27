<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMaintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_id',
        'deskripsi',
        'jumlah',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    public function barang()
        {
            return $this->belongsTo(Barang::class);
        }

}
