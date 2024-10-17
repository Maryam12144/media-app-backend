<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Entities\Traits\UuidModelTrait;


/**
 * Class BaseModel
 * @package Labs\Core\Entities
 */
class BaseModel extends Model
{
    use UuidModelTrait;

    protected $guard_name = 'api';
}