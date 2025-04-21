<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Translatable\HasTranslations;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasTranslations;


    protected $guarded = [];
    protected $appends = ['image_url'];

    public function getImageUrlAttribute(){
        return asset('images/users/' . $this->image);
    }

    public function refreshTokne()
    {
        return $this->hasMany(RefreshToken::class);
    }

    public function reviews(){
        return $this->hasMany(Review::class, 'user_id');
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }



























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
        'password' => 'hashed',
    ];
}
