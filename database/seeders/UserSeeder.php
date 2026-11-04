<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Admin / HRD
        User::create([
            'name' => 'Admin HRD',
            'email' => 'admin@hris.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'position' => 'Human Resources',
        ]);

        // Akun Karyawan
        User::create([
            'name' => 'Muhammad Ivan',
            'email' => 'ivan@karyawan.com',
            'password' => Hash::make('password123'),
            'role' => 'employee',
            'position' => 'Full Stack Developer',
        ]);
    }
}