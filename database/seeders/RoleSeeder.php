<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [

            [
                'role_name'   => 'Admin',
                'description' => 'Full access to the system',
                'is_active'   => true,
            ],

            [
                'role_name'   => 'Supervisor',
                'description' => 'Inspection approval and monitoring',
                'is_active'   => true,
            ],

            [
                'role_name'   => 'Operator',
                'description' => 'Forklift inspection operator',
                'is_active'   => true,
            ],

        ];

        foreach ($roles as $role) {

            Role::updateOrCreate(
                [
                    'role_name' => $role['role_name']
                ],
                $role
            );

        }
    }
}