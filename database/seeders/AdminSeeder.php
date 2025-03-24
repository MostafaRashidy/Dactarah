<?php

namespace Database\Seeders;

use App\Models\Admin;  // Add this import
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;  // Add this import

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
