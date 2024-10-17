<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;

/**
 * Class BaseUser
 * Labs\Core\Entities
 */
class BaseUser extends Authenticatable implements HasMedia
{
    use Notifiable, HasRoles, InteractsWithMedia, HasApiTokens;

    /**
     * @var string
     */
    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone_number', 'email', 'email_verified_at', 'password', 'lang',
        'first_name', 'last_name', 'status', 'avatar', 'trial_ends_at',
        'birthday', 'country', 'state', 'city', 'rating', 'status_updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Attributes that are searchable
     *
     * @var array
     */
    public $searchable = [
        'email', 'full_name', 'country',
        'state', 'city', 'phone_number'
    ];

    /**
     * Custom cast attributes
     *
     * @var array
     */
    protected $casts = [
        'is_superadmin' => 'boolean',
        'birthday' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    /**
     * Apply only admins on query
     *
     * @param Builder $query
     * @return mixed
     */
    public function scopeAdmin($query)
    {
        return $query->whereHas('roles', function ($q) {
            return $q->where('name', Role::ROLE_ADMIN);
        });
    }

    /**
     * Apply only members on query
     *
     * @param Builder $query
     * @return mixed
     */
    public function scopeMember($query)
    {
        return $query->whereDoesntHave('roles', function ($q) {
            return $q->where('name', 'Admin');
        });
    }

    /**
     * @return string
     */
    public function receivesBroadcastNotificationsOn()
    {
        return 'notifications_' . $this->id;
    }

}