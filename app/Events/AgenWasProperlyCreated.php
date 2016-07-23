<?php

namespace App\Events;

use App\Agen;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AgenWasProperlyCreated extends Event
{
    use SerializesModels;

    public $agen;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Agen $agen)
    {
        $this->agen = $agen;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
