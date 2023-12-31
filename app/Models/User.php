<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uaepass_id',
        'name',
        'email',
        'job_name',
        'password',
        'profile_image',
        'idn',
        'uuid',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // 'password' => 'hashed',
    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function loginTokens()
    {
        return $this->hasMany(LoginToken::class);
    }

    public function libraries()
    {
        return $this->hasMany(Library::class);
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
