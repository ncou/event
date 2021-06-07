<?php

declare(strict_types=1);

namespace Chiron\Event;

class ListenerData
{
    /**
     * @var string
     */
    public $event;

    /**
     * @var callable
     */
    public $listener;

    /**
     * @var int
     */
    public $priority;

    public function __construct(string $event, callable $listener, int $priority)
    {
        $this->event = $event;
        $this->listener = $listener;
        $this->priority = $priority;
    }
}
