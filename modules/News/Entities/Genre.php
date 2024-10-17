<?php

namespace Modules\News\Entities;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Genre extends Model
{
    use SoftDeletes;

    protected $fillable = ['name','is_active','is_default','sort_order','lang','added_by'];
    
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->sort_order = $model->max('id') + 1;
            $model->added_by = Auth::user()->id;
        });
    }
}
