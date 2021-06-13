<?php

declare(strict_types=1);

namespace Chiron\Event;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

//https://github.com/hyperf/hyperf/blob/master/src/event/src/EventDispatcher.php

class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var ListenerProviderInterface
     */
    private $listeners;

    // TODO : renommer le paramétre $listeners en $listener au singulier ou alors directement en $provider ou $listenerProvider
    // TODO : actualiser les tests de ce package !!!
    public function __construct(
        ListenerProviderInterface $listeners
    ) {
        $this->listeners = $listeners;
    }

    /**
     * Provide all listeners with an event to process.
     *
     * @param object $event The object to process
     * @return object The Event that was passed, now modified by listeners
     */
    public function dispatch(object $event)
    {

        // TODO : on devrait pas vérifier si la paramétre $event isPropagationStopped est activé avant le if et faire un break si c'est le cas ?

        foreach ($this->listeners->getListenersForEvent($event) as $listener) {
            $listener($event);
            if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                break;
            }
        }
        return $event;
    }
}
