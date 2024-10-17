<?php

namespace Modules\News\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatRoom extends Model
{
    use HasFactory;

    protected $guard_name = 'api';

    protected $fillable = ['evaluation_id','sender_id',
            'receiver_id','is_read'];
    
    protected static function newFactory()
    {
        return \Modules\News\Database\factories\ChatRoomFactory::new();
    }
}
