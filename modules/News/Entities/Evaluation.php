<?php

namespace Modules\News\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evaluation extends Model
{
    use SoftDeletes;

    protected $guard_name = 'api';

    protected $fillable = ['criteria','evaluator_id',
            'poster_id','news_id'];
    
    protected static function newFactory()
    {
        return \Modules\News\Database\factories\EvaluationFactory::new();
    }
}
