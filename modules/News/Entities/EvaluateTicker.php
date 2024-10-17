<?php

namespace Modules\News\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EvaluateTicker extends Model
{
    use HasFactory;

    protected $fillable = ['reason', 'evaluator_id', 'poster_id', 'ticker_id'];
    
    protected static function newFactory()
    {
        return \Modules\News\Database\factories\EvaluateTickerFactory::new();
    }
}
