<?php

namespace Modules\Core\Entities\Traits;

use Modules\Core\Entities\Config;

/**
 * Trait ConfigableTrait
 * @package Labs\Core\Entities\Traits
 */
trait ConfigableTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function configs()
    {
        return $this->morphToMany(Config::class, 'configable');
    }
}