<?php

namespace App\Http\Controllers;

use App\Models\Kbarang;

class KbarangController extends Controller
{
    public function index()
    {
        return Kbarang::all();
    }
}