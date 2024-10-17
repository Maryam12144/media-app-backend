<?php

namespace Modules\Core\Entities;

use Modules\Core\Entities\Traits\UuidModelTrait;
use Spatie\Permission\Models\Role as SpatieRole;

/**
 * Class Role
 * @package Labs\Core\Entities
 */
class Role extends SpatieRole implements \Spatie\Permission\Contracts\Role
{
    /**
     * User roles
     */
    const ROLE_ADMIN = 'Admin';
    const ROLE_NORMAL_ADMIN = 'Normal Admin';
    const ROLE_USER = 'User';

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'guard_name'
    ];

    /**
     * @var string
     */
    protected $guard_name = 'api';
}
