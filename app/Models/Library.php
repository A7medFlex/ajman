<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title_ar', 'title_en', 'body_ar', 'body_en', 'thumbnail'];

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
