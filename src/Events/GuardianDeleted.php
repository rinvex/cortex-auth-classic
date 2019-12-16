<?php

declare(strict_types=1);

namespace Cortex\Auth\Events;

use Cortex\Auth\Models\Guardian;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class GuardianDeleted implements ShouldBroadcast
{
    use SerializesModels;
    Use InteractsWithSockets;

    public $guardian;

    /**
     * Create a new event instance.
     *
     * @param \Cortex\Auth\Models\Guardian $guardian
     */
    public function __construct(Guardian $guardian)
    {
        $this->guardian = $guardian;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn()
    {
        return new Channel($this->formatChannelName());
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'cortex.auth.guardians.deleted';
    }

    /**
     * Format channel name.
     *
     * @return string
     */
    protected function formatChannelName(): string
    {
        return 'cortex.auth.guardians.list';
    }
}
