<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'barang_peminjaman';

    protected $fillable = [
        'peminjam_id',
        'barang_id',
        'kbarang_id',
        'jumlah',
        'tanggal_pinjam',
        'tanggal_pengembalian',
        'status',
    ];

    public function peminjam()
    {
        return $this->belongsTo(Peminjam::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
    public function kbarang()
    {
        return $this->belongsTo(\App\Models\Kbarang::class);
    }
}