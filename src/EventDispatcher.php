<?php

declare(strict_types=1);

namespace Chiron\Event;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;


//https://github.com/ventoviro/windwalker-event/blob/master/Dispatcher.php

//https://github.com/symfony/event-dispatcher/blob/5.3/EventDispatcher.php

//https://github.com/thephpleague/event/blob/master/src/EventDispatcher.php
//https://github.com/thephpleague/event/blob/6d6d88d3c398f4e32995fccd4ec50a5bdaef131b/src/ListenerPriority.php#L7
//https://github.com/thephpleague/event/blob/master/src/EventDispatcherAware.php


//https://github.com/hyperf/hyperf/blob/master/src/event/src/EventDispatcher.php

// TODO : prendre exemple ici pour ajouter des méthodes proxy pour attacher les événements directement sur le $this->listenerProvider ca sera plus simple d'utilisation !!!

class EventDispatcher implements EventDispatcherInterface
{
    /** @var ListenerProviderInterface */
    private $provider;

    public function __construct(ListenerProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Provide all listeners with an event to process.
     *
     * @param object $event The object to process
     *
     * @return object The Event that was passed, now modified by listeners
     */
    public function dispatch(object $event): object
    {
        // TODO : on devrait pas vérifier si la paramétre $event isPropagationStopped est activé avant le if et faire un break si c'est le cas ?
        //https://github.com/antidot-framework/antidot-event-dispatcher/blob/3.x.x/src/EventDispatcher.php#L29
        //https://github.com/phoole/event/blob/16c61a626483842d659ed920bd735710eba1d846/src/Dispatcher.php#L53
        //https://github.com/phly/phly-event-dispatcher/blob/145c15077003a7016e22981eaa13edcdbe04433a/src/EventDispatcher.php#L32
        //https://github.com/symfony/symfony/blob/e34cd7dd2c6d0b30d24cad443b8f964daa841d71/src/Symfony/Component/EventDispatcher/EventDispatcher.php#L224
        //https://github.com/yiisoft/event-dispatcher/blob/41b7ef783a4dc23c71230753726b0c6d3256c615/src/Dispatcher/Dispatcher.php#L28
        //https://github.com/thephpleague/event/blob/master/src/EventDispatcher.php#L28

        $listeners = $this->provider->getListenersForEvent($event);

        foreach ($listeners as $listener) {
            if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                return $event; // TODO : vérifier si un simple break; n'est pas suffisant ??? ca éviterai d'avoir deux return dans cette méthode !!!
            }
            $listener($event);
        }

        return $event;
    }
}
