<?php

namespace Modules\News\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChannelHasTickerLoop extends Model
{
    use HasFactory;

    protected $fillable = ['loop_count', 'start_time', 'ticker_duration', 'channel_id'];
    
    protected static function newFactory()
    {
        return \Modules\News\Database\factories\ChannelHasTickerLoopFactory::new();
    }
}
