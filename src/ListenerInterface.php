<?php

declare(strict_types=1);

namespace Chiron\Event;

// TODO : renommer cette classe en EventListenerInterface ??? pour que ce soit plus claire ?
interface ListenerInterface
{
    /**
     * @return array<sting> returns the events classname that you want to listen
     */
    public function listen(): array;

    /**
     * Handle the Event when the event is triggered, all listeners will
     * complete before the event is returned to the EventDispatcher.
     */
    public function process(object $event): void;
}
