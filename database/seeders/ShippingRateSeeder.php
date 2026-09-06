<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ShippingRate;

class ShippingRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ShippingRate::truncate();

        ShippingRate::insert([
            ['region' => 'north', 'type' => 'same_province', 'fee' => 30000, 'created_at' => now(), 'updated_at' => now()],
            ['region' => 'north', 'type' => 'different_province', 'fee' => 40000, 'created_at' => now(), 'updated_at' => now()],
            ['region' => 'central', 'type' => 'different_region', 'fee' => 50000, 'created_at' => now(), 'updated_at' => now()],
            ['region' => 'south', 'type' => 'different_region', 'fee' => 60000, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
