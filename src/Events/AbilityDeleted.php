<?php

declare(strict_types=1);

namespace Cortex\Auth\Events;

use Cortex\Auth\Models\Ability;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AbilityDeleted implements ShouldBroadcast
{
    use InteractsWithSockets;
    use Dispatchable;

    /**
     * The name of the queue on which to place the event.
     *
     * @var string
     */
    public $broadcastQueue = 'events';

    /**
     * The model instance passed to this event.
     *
     * @var \Cortex\Auth\Models\Ability
     */
    public Ability $model;

    /**
     * Create a new event instance.
     *
     * @param \Cortex\Auth\Models\Ability $ability
     */
    public function __construct(Ability $ability)
    {
        $this->model = $ability->withoutRelations();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|\Illuminate\Broadcasting\Channel[]
     */
    public function broadcastOn()
    {
        return [
            new PrivateChannel('cortex.auth.abilities.index'),
            new PrivateChannel("cortex.auth.abilities.{$this->model->getRouteKey()}"),
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'ability.deleted';
    }
}
