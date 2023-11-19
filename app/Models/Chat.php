<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [ 'title', 'description', 'is_open', 'user_id', 'is_released'];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
