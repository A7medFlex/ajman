<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class LoginToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'token' => 'hashed',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeValidate($query, $attributes)
    {
        $token = $query
            ->whereUserId($attributes['user_id'])
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if(! $token || ! Hash::check($attributes['token'], $token->token))  abort(Response::HTTP_UNAUTHORIZED);

        return $token;
    }
}
