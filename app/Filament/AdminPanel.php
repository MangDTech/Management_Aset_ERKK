<?php
// filepath: c:\xampp\htdocs\Management_Aset_ERKK\app\Filament\AdminPanel.php

namespace App\Filament;

class AdminPanel
{
    
    public static function getBrand(): string
    {
        return 'MA - ERKK'; // Teks fallback jika logo tidak ditampilkan
    }

    public static function getLogo(): string
    {
        return '/assets/logo.png'; // Path ke logo PNG
    }

    // public static function getFavicon(): ?string
    // {
    //     return '/assets/favicon.ico';
    // }
}