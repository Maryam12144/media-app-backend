<?php

namespace Modules\News\Entities;

use Auth;
use Illuminate\Database\Eloquent\Model;

class NewsHasReport extends Model
{

    protected $fillable = [
        'news_id',
        'report_type_id',
        'is_ptc',
        'is_relevant_footage',
        'is_on_spot',
        'is_sot',
        'is_closing',
        'is_header',
        'is_name_strip',
        'is_duration_60_to_90',
        'is_ticker',
    ];
    
    public $timestamps = false;
}
