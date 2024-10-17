<?php

namespace Modules\News\Entities;

use Auth;
use Illuminate\Database\Eloquent\Model;

class NewsHasPackage extends Model
{

    protected $fillable = [
        'news_id',
        'package_type_id',
        'is_bumper',
        'is_opening_ptc',
        'is_relevant_footage',
        'is_avo',
        'is_diff_version_of_narration',
        'is_reporter_own_narrative',
        'is_on_camera_bits',
        'is_music',
        'is_duration_120_to_180',
        'is_header',
        'is_name_strip',
        'is_ticker',
    ];
    
    public $timestamps = false;
}
