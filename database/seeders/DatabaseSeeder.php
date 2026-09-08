<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([

            /*
            |--------------------------------------------------------------------------
            | MASTER DATA
            |--------------------------------------------------------------------------
            */

            RoleSeeder::class,
            LocationSeeder::class,
            UserSeeder::class,

            ForkliftSeeder::class,

            InspectionCategorySeeder::class,
            InspectionItemsSeeder::class,

            /*
            |--------------------------------------------------------------------------
            | TRANSACTION DATA
            |--------------------------------------------------------------------------
            */

            InspectionSeeder::class,
            InspectionDetailSeeder::class,
            InspectionPhotoSeeder::class,
            ApprovalSeeder::class,

        ]);
    }
}