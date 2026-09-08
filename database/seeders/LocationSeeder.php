<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [

            [
                'location_code' => 'LD',
                'location_name' => 'Loading Dock',
                'function'      => 'Material Handling',
                'description'   => 'Area forklift untuk memindahkan bahan baku menuju proses produksi.',
                'is_active'     => true,
            ],

            [
                'location_code' => 'FG',
                'location_name' => 'Finished Goods',
                'function'      => 'Finished Product Handling',
                'description'   => 'Area forklift untuk memindahkan produk jadi menuju area penyimpanan atau pengiriman.',
                'is_active'     => true,
            ],

        ];

        foreach ($locations as $location) {

            Location::updateOrCreate(
                [
                    'location_code' => $location['location_code'],
                ],
                $location
            );

        }
    }
}