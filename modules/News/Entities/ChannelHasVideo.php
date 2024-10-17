<?php

namespace Modules\News\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChannelHasVideo extends Model
{
    use HasFactory;

    protected $fillable = ['video_id','channel_id'];
    
    protected static function newFactory()
    {
        return \Modules\News\Database\factories\ChannelHasVideoFactory::new();
    }
}
