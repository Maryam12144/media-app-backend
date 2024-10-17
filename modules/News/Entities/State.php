<?php

namespace Modules\News\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class State extends Model
{
    use HasFactory;
    protected $guard_name = 'api';

    protected $fillable = ['name','country_name'];

    
    protected static function newFactory()
    {
        return \Modules\News\Database\factories\StateFactory::new();
    }
}
