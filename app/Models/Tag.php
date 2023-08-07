<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'model'];

    public function tagable()
    {
        return $this->morphedByMany($this->model, 'tagable');
    }
}
