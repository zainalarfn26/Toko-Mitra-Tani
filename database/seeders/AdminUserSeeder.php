<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Toko',
            'email' => 'admin@mitratani.com',
            'password' => Hash::make('password'), 
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Kasir Toko',
            'email' => 'kasir@mitratani.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
        ]);
    }
}
