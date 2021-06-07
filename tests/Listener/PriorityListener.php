<?php

declare(strict_types=1);

namespace Chiron\Event\Test\Listener;

use Chiron\Event\ListenerInterface;
use Chiron\Event\Test\Event\PriorityEvent;

class PriorityListener implements ListenerInterface
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function listen(): array
    {
        return [
            PriorityEvent::class,
        ];
    }

    /**
     * @param PriorityEvent $event
     */
    public function process(object $event)
    {
        PriorityEvent::$result[] = $this->id;
    }
}
