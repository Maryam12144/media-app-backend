<?php

namespace Modules\News\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatBox extends Model
{
    use HasFactory;

    protected $guard_name = 'api';

    protected $fillable = ['message','chat_room_id',
            'sender_id','receiver_id'];
    
    protected static function newFactory()
    {
        return \Modules\News\Database\factories\ChatBoxFactory::new();
    }
}
