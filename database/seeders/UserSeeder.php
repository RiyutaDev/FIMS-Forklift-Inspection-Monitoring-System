<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Get Master Data
        |--------------------------------------------------------------------------
        */

        $loadingDock   = Location::where('location_code', 'LD')->first();
        $finishedGoods = Location::where('location_code', 'FG')->first();

        $adminRole      = Role::where('role_name', 'Admin')->firstOrFail();
        $supervisorRole = Role::where('role_name', 'Supervisor')->firstOrFail();
        $operatorRole   = Role::where('role_name', 'Operator')->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Administrator
        |--------------------------------------------------------------------------
        */

        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'employee_number' => 'EMP001',
                'name'            => 'Administrator',
                'username'        => 'admin',
                'email'           => 'admin@fims.local',
                'phone'           => '081111111111',
                'password'        => 'Password123!',
                'location_id'     => null,
                'role_id'         => $adminRole->id,
                'is_active'       => true,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Supervisor
        |--------------------------------------------------------------------------
        */

        User::updateOrCreate(
            ['username' => 'supervisor'],
            [
                'employee_number' => 'EMP002',
                'name'            => 'Supervisor Loading Dock',
                'username'        => 'supervisor',
                'email'           => 'supervisor@fims.local',
                'phone'           => '081111111112',
                'password'        => 'Password123!',
                'location_id'     => $loadingDock?->id,
                'role_id'         => $supervisorRole->id,
                'is_active'       => true,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Operator Loading Dock
        |--------------------------------------------------------------------------
        */

        User::updateOrCreate(
            ['username' => 'operator.ld'],
            [
                'employee_number' => 'EMP003',
                'name'            => 'Operator Loading Dock',
                'username'        => 'operator.ld',
                'email'           => 'operator.ld@fims.local',
                'phone'           => '081111111113',
                'password'        => 'Password123!',
                'location_id'     => $loadingDock?->id,
                'role_id'         => $operatorRole->id,
                'is_active'       => true,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Operator Finished Goods
        |--------------------------------------------------------------------------
        */

        User::updateOrCreate(
            ['username' => 'operator.fg'],
            [
                'employee_number' => 'EMP004',
                'name'            => 'Operator Finished Goods',
                'username'        => 'operator.fg',
                'email'           => 'operator.fg@fims.local',
                'phone'           => '081111111114',
                'password'        => 'Password123!',
                'location_id'     => $finishedGoods?->id,
                'role_id'         => $operatorRole->id,
                'is_active'       => true,
            ]
        );
    }
}