<?php

namespace Modules\News\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChannelHasVideoLoop extends Model
{
    use HasFactory;

    protected $fillable = ['loop_count', 'start_time', 'video_duration', 'channel_id'];
    
    protected static function newFactory()
    {
        return \Modules\News\Database\factories\ChannelHasVideoLoopFactory::new();
    }
}
