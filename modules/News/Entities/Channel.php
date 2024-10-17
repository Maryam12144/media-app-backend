<?php

namespace Modules\News\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Channel extends Model
{
    use HasFactory;

    protected $fillable = ['name',
    'city_id',
    'genre_id',
    "logo",
    "url"];
    
    protected static function newFactory()
    {
        return \Modules\News\Database\factories\ChannelFactory::new();
    }
}
