<?php

declare(strict_types=1);

namespace Chiron\Event;

use Hyperf\Contract\StdoutLoggerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

//https://github.com/hyperf/hyperf/blob/master/src/event/src/EventDispatcher.php

// TODO : Logger à corriger !!!!

class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var ListenerProviderInterface
     */
    private $listeners;

    /**
     * @var null|StdoutLoggerInterface
     */
    //private $logger;

    // TODO : renommer le paramétre $listeners en $listener au singulier ou alors directement en $provider ou $listenerProvider
    // TODO : virer le logger et actualiser les tests de ce package !!!
    /*
    public function __construct(
        ListenerProviderInterface $listeners,
        ?StdoutLoggerInterface $logger = null
    ) {
        */
    public function __construct(
        ListenerProviderInterface $listeners
    ) {
        $this->listeners = $listeners;
        //$this->logger = $logger;
    }

    /**
     * Provide all listeners with an event to process.
     *
     * @param object $event The object to process
     * @return object The Event that was passed, now modified by listeners
     */
    public function dispatch(object $event)
    {
        foreach ($this->listeners->getListenersForEvent($event) as $listener) {
            $listener($event);
            //$this->dump($listener, $event);
            if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                break;
            }
        }
        return $event;
    }

    /**
     * Dump the debug message if $logger property is provided.
     * @param mixed $listener
     */
    /*
    private function dump($listener, object $event)
    {
        if (! $this->logger instanceof StdoutLoggerInterface) {
            return;
        }
        $eventName = get_class($event);
        $listenerName = '[ERROR TYPE]';
        if (is_array($listener)) {
            $listenerName = is_string($listener[0]) ? $listener[0] : get_class($listener[0]);
        } elseif (is_string($listener)) {
            $listenerName = $listener;
        } elseif (is_object($listener)) {
            $listenerName = get_class($listener);
        }
        $this->logger->debug(sprintf('Event %s handled by %s listener.', $eventName, $listenerName));
    }*/
}
