<?php

namespace Modules\Core\Libraries;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * Filter the results
     *
     * @param Builder $query
     * @param QueryFilter $filter
     * @return Builder
     */
    public function scopeFilter(Builder $query, QueryFilter $filter)
    {
        return $filter->apply($query);
    }
}
