<?php

declare(strict_types=1);

namespace Chiron\Event;

use Psr\EventDispatcher\ListenerProviderInterface;
use SplPriorityQueue;

final class ListenerProvider implements ListenerProviderInterface
{
    /**
     * @var ListenerData[]
     */
    public $listeners = [];

    /**
     * @param object $event An event for which to return the relevant listeners
     * @return iterable[callable] An iterable (array, iterator, or generator) of callables.  Each
     *                            callable MUST be type-compatible with $event.
     */
    public function getListenersForEvent($event): iterable
    {
        $queue = new SplPriorityQueue();
        foreach ($this->listeners as $listener) {
            if ($event instanceof $listener->event) {
                $queue->insert($listener->listener, $listener->priority);
            }
        }
        return $queue;
    }

    public function add(string $event, callable $listener, int $priority = 1): void
    {
        $this->listeners[] = new ListenerData($event, $listener, $priority);
    }

    public function attach(ListenerInterface $listener, int $priority = 1): void
    {
        foreach ($listener->listen() as $event) {
            $this->add($event, [$listener, 'process'], $priority);
        }
    }
}
