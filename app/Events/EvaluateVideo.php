<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EvaluateVideo implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

   
    public $evaluation;
    public $msg;
    public $poster_id;
    public $channel_name;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($evaluation, $msg, $poster_id, $channel_name)
    {
        $this->evaluation = $evaluation;
        $this->msg = $msg;
        $this->poster_id = $poster_id;
        $this->channel_name = $channel_name;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['evaluate-video'];
    }

    public function broadcastAs()
    {
        return 'evaluate';
    }
}
