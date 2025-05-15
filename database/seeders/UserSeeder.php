<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            // Buat user admin
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'), // Ganti dengan password yang Anda inginkan
            ]);
    }
}