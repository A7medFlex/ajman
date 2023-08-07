<?php

namespace App\Policies;

use App\Models\Library;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LibraryPolicy
{
    public function update(User $user, Library $library): bool
    {
        return $user->id === $library->user_id || $user->is_admin;
    }

    public function delete(User $user, Library $library): bool
    {
        return $user->id === $library->user_id || $user->is_admin;
    }
}
