<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kbarang extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'Keterangan',
    ];

    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }
}
