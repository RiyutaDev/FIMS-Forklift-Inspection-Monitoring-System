<?php

namespace App\Services\Master;

use App\Models\Role;

class RoleService
{
    public function findById(int $id): ?Role
    {
        return Role::find($id);
    }
}
