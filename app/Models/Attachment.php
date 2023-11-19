<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'extension', 'path', 'attachable_type', 'attachable_id'];

    public function attachable()
    {
        return $this->morphTo();
    }

    public function getNameAttribute($value)
    {
        return str($value)->beforeLast('.');
    }
}
