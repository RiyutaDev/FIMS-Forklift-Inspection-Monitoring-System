<?php

namespace Database\Seeders;

use App\Models\Inspection;
use App\Models\InspectionDetail;
use App\Models\InspectionItem;
use Illuminate\Database\Seeder;

class InspectionDetailSeeder extends Seeder
{
    public function run(): void
    {
        $inspections = Inspection::all();

        $items = InspectionItem::orderBy('sort_order')->get();

        foreach ($inspections as $inspection) {

            foreach ($items as $item) {

                InspectionDetail::updateOrCreate(

                    [
                        'inspection_id' => $inspection->id,
                        'inspection_item_id' => $item->id,
                    ],

                    [
                        'result_status' => null,
                        'result_value'  => null,
                        'result_text'   => null,
                        'note'          => null,
                        'is_passed'     => null,
                    ]

                );

            }

        }

    }
}