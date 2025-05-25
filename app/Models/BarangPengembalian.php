<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class BarangPengembalian extends Model
{
    use HasFactory , LogsActivity;
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
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
