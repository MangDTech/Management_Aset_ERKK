<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'category_id',
        'kbarang_id',
        'kode_barang',
        'jumlah_barang',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function kbarang()
    {
        return $this->belongsTo(Kbarang::class);
    }
}
