<?php

declare(strict_types=1);

namespace Cortex\Auth\Events;

use Cortex\Auth\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserSaved implements ShouldBroadcast
{
    use SerializesModels;
    Use InteractsWithSockets;

    public $user;

    /**
     * Create a new event instance.
     *
     * @param \Cortex\Auth\Models\User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
        return 'cortex.auth.users.created';
    }

    /**
     * Format channel name.
     *
     * @return string
     */
    protected function formatChannelName(): string
    {
        return 'cortex.auth.users.count';
    }
}
