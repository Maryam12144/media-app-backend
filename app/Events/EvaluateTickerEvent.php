<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EvaluateTickerEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

   
    public $ticker;
    public $msg;
    public $evaluator_id;
    public $poster_id;
    public $status;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($ticker, $msg, $evaluator_id, $poster_id, $status)
    {
        $this->ticker = $ticker;
        $this->msg = $msg;
        $this->evaluator_id = $evaluator_id;
        $this->poster_id = $poster_id;
        $this->status = $status;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['evaluate-ticker'];
    }

    public function broadcastAs()
    {
        return 'evaluate';
    }
}
