<?php

namespace Modules\News\Entities;

use Auth;
use Illuminate\Database\Eloquent\Model;

class NewsHasProximity extends Model
{

    protected $fillable = [
    'news_id',
    'country',
    'state',
    'city',
    'coordinates',
];
public $timestamps = false; 
}

