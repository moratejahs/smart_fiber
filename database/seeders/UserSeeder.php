<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admininstrator',
            'phone_number' => '09123456789',
            'barangay' => 'Poblacion',
            'username' => 'admin',
            'password' => Hash::make('admin1234'), // Password: adminpassword
            'is_admin' => true,
        ]);

        // Normal user
        User::create([
            'name' => 'Juan Dela Cruz',
            'phone_number' => '09987654321',
            'barangay' => 'Alegria',
            'username' => 'juandelacruz',
            'password' => Hash::make('user12314'), // Password: userpassword
            'is_admin' => false,
        ]);
    }
}
