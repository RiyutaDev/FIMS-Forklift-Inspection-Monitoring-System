<?php

namespace Database\Seeders;

use App\Models\Approval;
use App\Models\Inspection;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class ApprovalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Get Supervisor Role
        |--------------------------------------------------------------------------
        */

        $supervisorRole = Role::where('role_name', 'Supervisor')->first();

        if (!$supervisorRole) {

            $this->command->warn('Role Supervisor tidak ditemukan.');

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Get Active Supervisor
        |--------------------------------------------------------------------------
        */

        $supervisor = User::where('role_id', $supervisorRole->id)
            ->where('is_active', true)
            ->first();

        if (!$supervisor) {

            $this->command->warn('User Supervisor tidak ditemukan.');

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Submitted Inspection
        |--------------------------------------------------------------------------
        */

        $inspections = Inspection::submitted()->get();

        foreach ($inspections as $inspection) {

            Approval::updateOrCreate(

                [
                    'inspection_id' => $inspection->id,
                ],

                [

                    'approved_by'     => $supervisor->id,

                    'approval_status' => 'Approved',

                    'approval_note'   => 'Inspection has been reviewed and approved.',

                    'approved_at'     => now(),

                ]

            );

            /*
            |--------------------------------------------------------------------------
            | Synchronize Inspection Status
            |--------------------------------------------------------------------------
            */

            $inspection->update([

                'status' => 'Approved',

            ]);
        }

        $this->command->info('Approval Seeder berhasil dijalankan.');
    }
}