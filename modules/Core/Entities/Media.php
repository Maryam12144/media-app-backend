<?php

namespace Modules\Core\Entities;

use Modules\Core\Entities\Traits\UuidModelTrait;
use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;

/**
 * Class Media
 * @package Labs\Core\Entities
 */
class Media extends SpatieMedia
{
    use UuidModelTrait;

    protected $guard_name = 'api';

}
