<?php

namespace Database\Seeders;

use App\Models\Forklift;
use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ForkliftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = Location::pluck('id', 'location_code')->toArray();

        $forklifts = [

            [
                'location_code'      => 'LD',
                'forklift_code'      => 'FL-001',
                'asset_number'       => 'LD-FL-001',
                'brand'              => 'Toyota',
                'model'              => '8FBE20',
                'serial_number'      => 'TYT-2024-001',
                'manufacture_year'   => 2023,
                'commissioning_date' => '2024-01-15',
                'capacity'           => 2.50,
                'fuel_type'          => 'Electric',
                'vendor_name'        => 'PT Toyota Material Handling',
            ],

            [
                'location_code'      => 'LD',
                'forklift_code'      => 'FL-002',
                'asset_number'       => 'LD-FL-002',
                'brand'              => 'Toyota',
                'model'              => '02-8FD30',
                'serial_number'      => 'TYT-2023-002',
                'manufacture_year'   => 2022,
                'commissioning_date' => '2023-06-01',
                'capacity'           => 3.00,
                'fuel_type'          => 'Diesel',
                'vendor_name'        => 'PT Toyota Material Handling',
            ],

            [
                'location_code'      => 'FG',
                'forklift_code'      => 'FL-003',
                'asset_number'       => 'FG-FL-001',
                'brand'              => 'Toyota',
                'model'              => '8FBE20',
                'serial_number'      => 'TYT-2024-003',
                'manufacture_year'   => 2024,
                'commissioning_date' => '2024-08-10',
                'capacity'           => 2.50,
                'fuel_type'          => 'Electric',
                'vendor_name'        => 'PT Toyota Material Handling',
            ],

        ];

        foreach ($forklifts as $forklift) {

            $model = Forklift::updateOrCreate(

                [
                    'forklift_code' => $forklift['forklift_code'],
                ],

                [
                    'location_id'         => $locations[$forklift['location_code']],
                    'asset_number'        => $forklift['asset_number'],
                    'brand'               => $forklift['brand'],
                    'model'               => $forklift['model'],
                    'serial_number'       => $forklift['serial_number'],
                    'manufacture_year'    => $forklift['manufacture_year'],
                    'commissioning_date'  => $forklift['commissioning_date'],
                    'capacity'            => $forklift['capacity'],
                    'fuel_type'           => $forklift['fuel_type'],
                    'vendor_name'         => $forklift['vendor_name'],
                    'last_inspection_at'  => null,
                    'is_active'           => true,
                    'description'         => null,
                ]

            );

            // Generate QR Token hanya sekali
            if (!$model->qr_token) {

                $model->qr_token = (string) Str::uuid();

                $model->save();

            }
        }
    }
}