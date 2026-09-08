<?php

namespace Database\Seeders;

use App\Models\InspectionDetail;
use App\Models\InspectionPhoto;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class InspectionPhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Get Operator Role
        |--------------------------------------------------------------------------
        */

        $operatorRole = Role::where('role_name', 'Operator')->first();

        if (!$operatorRole) {
            $this->command->warn('Role Operator tidak ditemukan.');
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Get Active Operator
        |--------------------------------------------------------------------------
        */

        $operator = User::where('role_id', $operatorRole->id)
            ->where('is_active', true)
            ->first();

        if (!$operator) {
            $this->command->warn('Operator tidak ditemukan.');
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Inspection Details
        |--------------------------------------------------------------------------
        */

        $details = InspectionDetail::with('inspectionItem')->get();

        foreach ($details as $detail) {

            /*
            |--------------------------------------------------------------------------
            | Skip jika item tidak ada
            |--------------------------------------------------------------------------
            */

            if (!$detail->inspectionItem) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Skip jika tidak membutuhkan foto
            |--------------------------------------------------------------------------
            */

            if (!$detail->inspectionItem->requires_photo) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Create Sample Photo
            |--------------------------------------------------------------------------
            */

            InspectionPhoto::updateOrCreate(

                [
                    'inspection_detail_id' => $detail->id,
                    'photo_name'           => 'sample-photo.jpg',
                ],

                [

                    'photo_path'  => 'inspection/sample-photo.jpg',

                    'mime_type'   => 'image/jpeg',

                    'photo_size'  => 245760,

                    'caption'     => 'Sample inspection evidence',

                    'taken_at'    => now(),

                    'uploaded_by' => $operator->id,

                ]

            );
        }

        $this->command->info('Inspection Photo Seeder berhasil dijalankan.');
    }
}