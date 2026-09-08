<?php

namespace Database\Seeders;

use App\Models\InspectionCategory;
use App\Models\InspectionItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class InspectionItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = InspectionCategory::pluck('id', 'category_code');

        $items = [

            /*
            |--------------------------------------------------------------------------
            | General Condition
            |--------------------------------------------------------------------------
            */
            [
                'category' => 'GEN',
                'code' => 'GEN001',
                'name' => 'Body Condition',
                'fuel' => 'All',
                'critical' => false,
                'order' => 1,
            ],
            [
                'category' => 'GEN',
                'code' => 'GEN002',
                'name' => 'Fork Condition',
                'fuel' => 'All',
                'critical' => true,
                'order' => 2,
            ],
            [
                'category' => 'GEN',
                'code' => 'GEN003',
                'name' => 'Mast Condition',
                'fuel' => 'All',
                'critical' => true,
                'order' => 3,
            ],
            [
                'category' => 'GEN',
                'code' => 'GEN004',
                'name' => 'Tyre Condition',
                'fuel' => 'All',
                'critical' => true,
                'order' => 4,
            ],
            [
                'category' => 'GEN',
                'code' => 'GEN005',
                'name' => 'Overhead Guard',
                'fuel' => 'All',
                'critical' => true,
                'order' => 5,
            ],

            /*
            |--------------------------------------------------------------------------
            | Safety Equipment
            |--------------------------------------------------------------------------
            */
            [
                'category' => 'SAF',
                'code' => 'SAF001',
                'name' => 'Horn',
                'fuel' => 'All',
                'critical' => true,
                'order' => 1,
            ],
            [
                'category' => 'SAF',
                'code' => 'SAF002',
                'name' => 'Seat Belt',
                'fuel' => 'All',
                'critical' => true,
                'order' => 2,
            ],
            [
                'category' => 'SAF',
                'code' => 'SAF003',
                'name' => 'Head Lamp',
                'fuel' => 'All',
                'critical' => false,
                'order' => 3,
            ],
            [
                'category' => 'SAF',
                'code' => 'SAF004',
                'name' => 'Beacon Lamp',
                'fuel' => 'All',
                'critical' => false,
                'order' => 4,
            ],
            [
                'category' => 'SAF',
                'code' => 'SAF005',
                'name' => 'Reverse Alarm',
                'fuel' => 'All',
                'critical' => true,
                'order' => 5,
            ],

            /*
            |--------------------------------------------------------------------------
            | Operational System
            |--------------------------------------------------------------------------
            */
            [
                'category' => 'OPS',
                'code' => 'OPS001',
                'name' => 'Steering',
                'fuel' => 'All',
                'critical' => true,
                'order' => 1,
            ],
            [
                'category' => 'OPS',
                'code' => 'OPS002',
                'name' => 'Service Brake',
                'fuel' => 'All',
                'critical' => true,
                'order' => 2,
            ],
            [
                'category' => 'OPS',
                'code' => 'OPS003',
                'name' => 'Parking Brake',
                'fuel' => 'All',
                'critical' => true,
                'order' => 3,
            ],
            [
                'category' => 'OPS',
                'code' => 'OPS004',
                'name' => 'Lift Control Lever',
                'fuel' => 'All',
                'critical' => true,
                'order' => 4,
            ],
            [
                'category' => 'OPS',
                'code' => 'OPS005',
                'name' => 'Tilt Control Lever',
                'fuel' => 'All',
                'critical' => true,
                'order' => 5,
            ],

            /*
            |--------------------------------------------------------------------------
            | Hydraulic System
            |--------------------------------------------------------------------------
            */
            [
                'category' => 'HYD',
                'code' => 'HYD001',
                'name' => 'Hydraulic Hose',
                'fuel' => 'All',
                'critical' => true,
                'order' => 1,
            ],
            [
                'category' => 'HYD',
                'code' => 'HYD002',
                'name' => 'Hydraulic Cylinder',
                'fuel' => 'All',
                'critical' => true,
                'order' => 2,
            ],
            [
                'category' => 'HYD',
                'code' => 'HYD003',
                'name' => 'Hydraulic Leakage',
                'fuel' => 'All',
                'critical' => true,
                'order' => 3,
            ],

            /*
            |--------------------------------------------------------------------------
            | Power System (Electric)
            |--------------------------------------------------------------------------
            */
            [
                'category' => 'PWR',
                'code' => 'PWR001',
                'name' => 'Battery Condition',
                'fuel' => 'Electric',
                'critical' => true,
                'order' => 1,
            ],
            [
                'category' => 'PWR',
                'code' => 'PWR002',
                'name' => 'Battery Connector',
                'fuel' => 'Electric',
                'critical' => true,
                'order' => 2,
            ],
            [
                'category' => 'PWR',
                'code' => 'PWR003',
                'name' => 'Battery Water',
                'fuel' => 'Electric',
                'critical' => false,
                'order' => 3,
            ],

            /*
            |--------------------------------------------------------------------------
            | Power System (Diesel)
            |--------------------------------------------------------------------------
            */
            [
                'category' => 'PWR',
                'code' => 'PWR004',
                'name' => 'Engine Oil',
                'fuel' => 'Diesel',
                'critical' => true,
                'order' => 4,
            ],
            [
                'category' => 'PWR',
                'code' => 'PWR005',
                'name' => 'Radiator Coolant',
                'fuel' => 'Diesel',
                'critical' => true,
                'order' => 5,
            ],
            [
                'category' => 'PWR',
                'code' => 'PWR006',
                'name' => 'Fuel Level',
                'fuel' => 'Diesel',
                'critical' => false,
                'order' => 6,
            ],

            /*
            |--------------------------------------------------------------------------
            | Attachment
            |--------------------------------------------------------------------------
            */
            [
                'category' => 'ATT',
                'code' => 'ATT001',
                'name' => 'Fork Lock',
                'fuel' => 'All',
                'critical' => true,
                'order' => 1,
            ],
            [
                'category' => 'ATT',
                'code' => 'ATT002',
                'name' => 'Lift Chain',
                'fuel' => 'All',
                'critical' => true,
                'order' => 2,
            ],
            [
                'category' => 'ATT',
                'code' => 'ATT003',
                'name' => 'Mast Roller',
                'fuel' => 'All',
                'critical' => false,
                'order' => 3,
            ],

            /*
            |--------------------------------------------------------------------------
            | Documentation
            |--------------------------------------------------------------------------
            */
            [
                'category' => 'DOC',
                'code' => 'DOC001',
                'name' => 'Forklift Identification',
                'fuel' => 'All',
                'critical' => false,
                'order' => 1,
            ],
            [
                'category' => 'DOC',
                'code' => 'DOC002',
                'name' => 'Daily Inspection Form',
                'fuel' => 'All',
                'critical' => false,
                'order' => 2,
            ],
        ];

        foreach ($items as $item) {

            InspectionItem::updateOrCreate(

                [
                    'item_code' => $item['code']
                ],

                [
                    'inspection_category_id' => $categories[$item['category']],
                    'item_name' => $item['name'],
                    'description' => null,
                    'applicable_fuel_type' => $item['fuel'],
                    'input_type' => 'OK_NG',
                    'unit' => null,
                    'is_critical' => $item['critical'],
                    'requires_photo' => false,
                    'requires_note' => true,
                    'sort_order' => $item['order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
