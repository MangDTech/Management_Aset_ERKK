<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Peminjam extends Authenticatable
{
    use HasFactory;

    protected $fillable= [
        'name',
        'email',
        'password',
        'is_verified',
        
    ];
     public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    
    public function otps()
    {
        return $this->hasMany(Otp::class);
    }
}
