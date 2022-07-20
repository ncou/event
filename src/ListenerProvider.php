<?php

declare(strict_types=1);

namespace Chiron\Event;

use Psr\EventDispatcher\ListenerProviderInterface;

//https://github.com/hyperf/hyperf/blob/master/src/event/src/ListenerProvider.php

final class ListenerProvider implements ListenerProviderInterface
{
    /** @var array */
    private array $listeners = [];

    /**
     * @param object $event An event for which to return the relevant listeners
     *
     * @return iterable<callable> An iterable (array, iterator, or generator) of callables.  Each
     *                            callable MUST be type-compatible with $event.
     */
    public function getListenersForEvent(object $event): iterable
    {
        foreach ($this->listeners as $type => $listeners) {
            if ($event instanceof $type) {
                yield from $listeners;
            }
        }
    }

    // TODO : ajouter 'Listener' dans le nom des méthodes style addListener / attachListener ????
    public function add(ListenerInterface $listener): void
    {
        foreach ($listener->listen() as $event) {
            $this->attach($event, [$listener, 'process']);
        }
    }

    // TODO : ajouter 'Listener' dans le nom des méthodes style addListener / attachListener ????
    public function attach(string $event, callable $listener): void
    {
        $this->listeners[$event][] = $listener;
    }
}
