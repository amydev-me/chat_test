<?php

namespace App\Events;

use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Http\Request;
class MessageNotify implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $request;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request->all();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['chat-channel-' . $this->request['receiver_id']];
    }

    public function broadcastWith()
    {

        return [
            'receiver_id' => $this->request['receiver_id'],
            'send_at' => Carbon::now(),
            'message' => $this->request['message']
        ];
    }
}