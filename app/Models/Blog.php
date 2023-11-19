<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title_ar', 'title_en', 'body_ar', 'body_en', 'thumbnail', 'is_released'];

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'tagable');
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

    public function getTitleAttribute()
    {
        $locale = app()->getLocale();

        $title = $locale === 'ar' ? $this->title_ar : $this->title_en;

        return $title ?? $this->title_ar;
    }

    public function getBodyAttribute()
    {
        $locale = app()->getLocale();

        $body = $locale === 'ar' ? $this->body_ar : $this->body_en;

        return $body ?? $this->body_ar;
    }
}
