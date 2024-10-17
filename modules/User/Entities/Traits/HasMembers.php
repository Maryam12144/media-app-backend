<?php

namespace Modules\User\Entities\Traits;

use App\User;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasMembers
{
    /**
     * Get the company members
     *
     * @return MorphToMany
     */
    public function members()
    {
        return $this->morphToMany(User::class,
            'memberable', 'members');
    }
}