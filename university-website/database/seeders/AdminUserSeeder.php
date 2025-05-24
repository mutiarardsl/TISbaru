<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        
        // Tambahkan staff fakultas
        User::create([
            'name' => 'Staff Fakultas',
            'email' => 'staff@faculty.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);
        
        // Tambahkan staff universitas
        User::create([
            'name' => 'Staff Universitas',
            'email' => 'staff@university.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);
    }
}