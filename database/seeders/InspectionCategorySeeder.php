<?php

namespace Database\Seeders;

use App\Models\InspectionCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class InspectionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        $categories = [

            [
                'category_code' => 'GEN',
                'category_name' => 'General Condition',
                'description'   => 'General physical condition of the forklift.',
                'sort_order'    => 1,
            ],

            [
                'category_code' => 'SAF',
                'category_name' => 'Safety Equipment',
                'description'   => 'Inspection of safety devices and equipment.',
                'sort_order'    => 2,
            ],

            [
                'category_code' => 'OPS',
                'category_name' => 'Operational System',
                'description'   => 'Inspection of operational control systems.',
                'sort_order'    => 3,
            ],

            [
                'category_code' => 'HYD',
                'category_name' => 'Hydraulic System',
                'description'   => 'Inspection of hydraulic components.',
                'sort_order'    => 4,
            ],

            [
                'category_code' => 'PWR',
                'category_name' => 'Power System',
                'description'   => 'Inspection of power source (Electric / Diesel).',
                'sort_order'    => 5,
            ],

            [
                'category_code' => 'ATT',
                'category_name' => 'Attachment',
                'description'   => 'Inspection of mast, fork, chain and lifting attachments.',
                'sort_order'    => 6,
            ],

            [
                'category_code' => 'DOC',
                'category_name' => 'Documentation',
                'description'   => 'Inspection of operational documents and identification.',
                'sort_order'    => 7,
            ],

        ];

        foreach ($categories as $category) {

            InspectionCategory::updateOrCreate(

                [
                    'category_code' => $category['category_code']
                ],

                array_merge($category, [
                    'is_active' => true
                ])
            );
        }
    }
}
