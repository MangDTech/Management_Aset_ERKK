<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangPengembalian extends Model
{
    use HasFactory;
    protected $table = 'barang_pengembalian';
    protected $fillable = [
        'peminjam_id',
        'barang_id',
        'kbarang_id',
        'jumlah',
        'tanggal_pinjam',
        'tanggal_pengembalian',
        'status'
    ];


    public function peminjam() { return $this->belongsTo(Peminjam::class, 'peminjam_id'); }
    public function barang() { return $this->belongsTo(Barang::class, 'barang_id'); }
    public function kbarang() { return $this->belongsTo(Kbarang::class, 'kbarang_id'); }
}
