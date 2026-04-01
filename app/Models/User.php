<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'provider_type',
        'provider_id',
        'avatar',
        'email_verified_at',
        'phone',
        'photo',
        'latitude',
        'longitude',
        'is_active'
    ];
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function pharmacy()
    {
        return $this->hasOne(Pharmacy::class);
    }
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
