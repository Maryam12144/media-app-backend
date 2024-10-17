<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Modules\Core\Entities\Traits\UuidModelTrait;


/**
 * Class BasePivotModel
 * @package Labs\Core\Entities
 */
class BasePivotModel extends Pivot
{
    use UuidModelTrait;

    protected $guard_name = 'api';
}