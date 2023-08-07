<?php

namespace App\Policies;

use App\Models\Attachment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AttachmentPolicy
{
    public function delete(User $user, Attachment $attachment): bool
    {
        return $user->id === $attachment->attachable->user_id || $user->is_admin;
    }
}
