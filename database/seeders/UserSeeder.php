<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Toronja',
            'email' => 'admin@toronja.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => true,
        ]);

        User::create([
            'name' => 'Empleado 1',
            'email' => 'empleado1@toronja.com',
            'password' => Hash::make('empleado123'),
            'role' => 'employee',
            'status' => true,
        ]);
    }
}
