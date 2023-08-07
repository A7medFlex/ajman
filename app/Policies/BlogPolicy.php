<?php

namespace App\Policies;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BlogPolicy
{
    
    public function update(User $user, Blog $blog): bool
    {
        return $user->id === $blog->user_id || $user->is_admin;
    }

    public function delete(User $user, Blog $blog): bool
    {
        return $user->id === $blog->user_id || $user->is_admin;
    }
}
