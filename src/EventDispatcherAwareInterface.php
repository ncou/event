<?php

declare(strict_types=1);

namespace Chiron\Event;

use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Defines the interface for a EventDispatcher Aware class.
 * The "getEventDispatcher()" function is protected so we don't add it to the interface definition.
 */
interface EventDispatcherAwareInterface
{
    /**
     * Set the Event Dispatcher.
     *
     * @param EventDispatcherInterface $dispatcher
     *
     * @return self
     */
    public function setEventDispatcher(EventDispatcherInterface $dispatcher): self;

    /**
     * Indicates if the event dispatcher is defined.
     *
     * @return bool
     */
    public function hasEventDispatcher(): bool;
}
