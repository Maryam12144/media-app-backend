<?php

namespace Modules\News\Entities;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use SoftDeletes;

    protected $guard_name = 'api';

    protected $fillable = ['user_id','video_type_id','genre_id','geographical_criteria_id',
            'is_celebrity','celebrity_genre_id','celebrity_name','prominence_id','is_controversy',
            'human_interest_id', 'news_type_id', 'video_name','video_path','is_active','sort_order', 'ticker_text',
            'loop_sequence','news_duration','news_loop_id','news_length','ticker_duration'];
    
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->sort_order = $model->max('id') + 1;
            $model->user_id = Auth::user()->id;
        });
    }
}
