<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable =['user_id', 'thumbnail','title', 'details', 'start', 'end', 'is_released'];

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function togglelike($user = null)
    {
        $user = $user ?: auth()->user();
        return $this->likes()->toggle($user);
    }

    public function isLiked($user = null)
    {
        $user = $user ?: auth()->user();
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function likes()
    {
        return $this->morphToMany(User::class, 'likeable')->withTimestamps();
    }
}
