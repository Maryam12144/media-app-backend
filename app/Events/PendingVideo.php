<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PendingVideo implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

   
    public $news;
    public $evaluator_id;
    public $duration;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($news, $evaluator_id, $duration)
    {
        $this->news = $news;
        $this->evaluator_id = $evaluator_id;
        $this->duration = $duration;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['pending-video'];
    }

    public function broadcastAs()
    {
        return 'pending';
    }
}  
