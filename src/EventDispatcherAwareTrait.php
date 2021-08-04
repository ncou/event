<?php

declare(strict_types=1);

namespace Chiron\Event;

use Psr\EventDispatcher\EventDispatcherInterface;
use UnexpectedValueException;

/**
 * Defines the trait for a EventDispatcher Aware Class.
 */
trait EventDispatcherAwareTrait
{
    /** @var EventDispatcherInterface */
    protected $dispatcher;

    /**
     * Set the EventDispatcher.
     *
     * @param EventDispatcherInterface $dispatcher
     *
     * @return self
     */
    public function setEventDispatcher(EventDispatcherInterface $dispatcher): EventDispatcherAwareInterface
    {
        $this->dispatcher = $dispatcher;

        return $this;
    }

    /**
     * Indicates if the event dispatcher is defined.
     *
     * @return bool
     */
    public function hasEventDispatcher(): bool
    {
        return $this->dispatcher instanceof EventDispatcherInterface;
    }

    /**
     * Get the event dispatcher instance. Only in a protected way, it's not necessary to be public.
     *
     * @throws UnexpectedValueException May be thrown if the event dispatcher has not been set.
     *
     * @return EventDispatcherInterface
     */
    protected function getEventDispatcher(): EventDispatcherInterface
    {
        if ($this->hasEventDispatcher()) {
            return $this->dispatcher;
        }

        // TODO : faire un throw new MissingEventDispatcherException('xxx');
        throw new UnexpectedValueException(sprintf('EventDispatcher implementation not set in "%s".', static::class));
    }
}
