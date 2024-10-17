<?php

namespace Modules\News\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticker extends Model
{
    use HasFactory;

    protected $fillable = [
    'ticker',
    'start_time',
    'end_time',
    'start_date',
    'end_date',
    'status',
    'channel_id'
    ];
    
    protected static function newFactory()
    {
        return \Modules\News\Database\factories\TickerFactory::new();
    }
}
