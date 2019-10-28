<?php

namespace LoiPham\Media\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MediaZipProgress implements ShouldBroadcastNow
{
    protected $user;
    public $data;

    /**
     * Create a new event instance.
     *
     * @param mixed $data
     */
    public function __construct($data)
    {
        $this->user = auth()->user();
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel("User.{$this->user->id}.media");
    }

    public function broadcastAs()
    {
        return 'user.media.zip';
    }
}
