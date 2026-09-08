<?php

namespace App\Services\Master;

use App\Models\User;

class UserService
{
    public function findById(int $id): ?User
    {
        return User::find($id);
    }
}
