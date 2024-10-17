<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

   
    public $evaluation_id;
    public $sender_id;
    public $receiver_id;
    public $chat_room_id;
    
    public $message;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($evaluation_id, $sender_id, $receiver_id, $chat_room_id, $message)
    {
        $this->evaluation_id = $evaluation_id;
        $this->sender_id = $sender_id;
        $this->receiver_id = $receiver_id;
        $this->chat_room_id = $chat_room_id;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['conversation'];
    }

    public function broadcastAs()
    {
        return 'message';
    }
}
