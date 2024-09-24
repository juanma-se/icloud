<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    const ADMIN_ROLE = 'administrator';

    const RESPONSIBLE_ROLE = 'responsible';

    const ASSIGN_ROLE = 'assign';

    const PERMISSIONS = [
        'view',
        'edit',
        'create',
        'delete'
    ];

    const ADMIN_ROLE_PERMISSIONS = [
        'view',
        'edit',
        'create',
        'delete'
    ];

    const RESPONSIBLE_ROLE_PERMISSIONS = [
        'view',
        'create'
    ];

    const ASSIGN_ROLE_PERMISSIONS = [
        'view'
    ];

    const COMPOSE_PERMISSIONS_AND_ROLES = [
        'administrator' => self::ADMIN_ROLE_PERMISSIONS,
        'responsible' => self::RESPONSIBLE_ROLE_PERMISSIONS,
        'assign' => self::ASSIGN_ROLE_PERMISSIONS,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
