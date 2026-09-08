<?php

namespace Database\Seeders;

use App\Models\Forklift;
use App\Models\Inspection;
use App\Models\Role;
use App\Models\User;
use App\Services\InspectionNumberService;
use Illuminate\Database\Seeder;

class InspectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inspectionDate = now()->toDateString();

        /*
        |--------------------------------------------------------------------------
        | Operator Role
        |--------------------------------------------------------------------------
        */

        $operatorRole = Role::where('role_name', 'Operator')->first();

        if (!$operatorRole) {

            $this->command->error('Role Operator tidak ditemukan.');

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Active Forklifts
        |--------------------------------------------------------------------------
        */

        $forklifts = Forklift::with('location')
            ->active()
            ->orderBy('location_id')
            ->orderBy('forklift_code')
            ->get();

        foreach ($forklifts as $forklift) {

            /*
            |--------------------------------------------------------------------------
            | Find Operator by Location
            |--------------------------------------------------------------------------
            */

            $operator = User::where('role_id', $operatorRole->id)
                ->where('location_id', $forklift->location_id)
                ->where('is_active', true)
                ->first();

            if (!$operator) {

                $this->command->warn(
                    "Operator tidak ditemukan untuk lokasi {$forklift->location->location_code}"
                );

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Create Draft Inspection
            |--------------------------------------------------------------------------
            */

            Inspection::updateOrCreate(

                [

                    'forklift_id'      => $forklift->id,
                    'inspection_date'  => $inspectionDate,
                    'inspection_shift' => 'Shift 1',

                ],

                [

                    'inspection_number'        => InspectionNumberService::generate($forklift),

                    'operator_id'              => $operator->id,

                    'inspection_started_at'    => null,

                    'inspection_completed_at'  => null,

                    'overall_result'           => null,

                    'status'                   => 'Draft',

                    'remarks'                  => null,

                    'submitted_by'             => null,

                    'submitted_at'             => null,

                ]

            );
        }

        $this->command->info('Inspection Seeder berhasil dijalankan.');
    }
}