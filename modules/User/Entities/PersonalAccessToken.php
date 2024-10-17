<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\PersonalAccessToken as Original;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

class PersonalAccessToken extends Original
{
    use HasFactory, HasEagerLimit;

    /**
     * Mass-assignable attributes
     *
     * @var array
     */
    protected $fillable = [
        'name', 'token', 'abilities',
    ];
}
