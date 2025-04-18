<?php

namespace Database\Seeders;

use App\Models\Barangay;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BarangaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barangays = [
            ['name' => 'Alegria'],
            ['name' => 'Arorogan'],
            ['name' => 'Antipolo'],
            ['name' => 'Amontay'],
            ['name' => 'Bayan'],
            ['name' => 'Mararag'],
            ['name' => 'San Antonio'],
            ['name' => 'San Isidro'],
            ['name' => 'Santa Cruz'],
            ['name' => 'Mahaba'],
            ['name' => 'San Pedro'],
            ['name' => 'Poblacion'],
        ];
        foreach ($barangays as $barangay) {
            Barangay::create([
                'name' => $barangay['name']
            ]);
        }
    }
}
