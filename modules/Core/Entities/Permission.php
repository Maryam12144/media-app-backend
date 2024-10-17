<?php

namespace Modules\Core\Entities;

use Modules\Core\Entities\Traits\UuidModelTrait;
use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * Class Permission
 * @package Labs\Core\Entities
 */
class Permission extends SpatiePermission implements \Spatie\Permission\Contracts\Permission
{
    /**
     * @var array
     */
    protected $fillable = [
        'name', 'title', 'guard_name', 'group_name'
    ];

    /**
     * @var string
     */
    protected $guard_name = 'api';
}
