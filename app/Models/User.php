<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    const ADMIN_ROLE = 'administrador';

    const RESPONSIBLE_ROLE = 'responsable';

    const ASSIGN_ROLE = 'asignado';

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
        'administrador' => self::ADMIN_ROLE_PERMISSIONS,
        'responsable' => self::RESPONSIBLE_ROLE_PERMISSIONS,
        'asignado' => self::ASSIGN_ROLE_PERMISSIONS,
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
            'email_verified_at' => 'datetime:d-m-Y H:i:s',
            'created_at'        => 'datetime:d-m-Y H:i:s',
            'updated_at'        => 'datetime:d-m-Y H:i:s',
            'password'          => 'hashed',
        ];
    }

    /**
     * Interact with email_verified_at.
     */
    protected function emailVerifiedAt(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => now(),
        );
    }

    /**
     * Interact with email_verified_at.
     */
    protected function rememberToken(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Str::random(10),
        );
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function (User $user) {
            if ($user->remember_token == null) {
                $user->remember_token = Str::random(10);
                $user->email_verified_at = now();
                $user->save();
            }
        });
    }
}
