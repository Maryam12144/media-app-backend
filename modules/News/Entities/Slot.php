<?php

namespace Modules\News\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slot extends Model
{
    use HasFactory;

    protected $fillable = ['start_time', 'end_time',  'start_date', 'end_date',
     'slot_type_id', 'archive_id', 'duration', 'channel_id'];
    
    protected static function newFactory()
    {
        return \Modules\News\Database\factories\SlotFactory::new();
    }
}
