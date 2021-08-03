<?php

declare(strict_types=1);

namespace Chiron\Event;

// TODO : utiliser plutot cette classe et la transformer en trait !!! : https://github.com/yiisoft/event-dispatcher/blob/41b7ef783a4dc23c71230753726b0c6d3256c615/tests/Event/StoppableEvent.php

// TODO : utilité trés limité pour cette classe à voir si on la conserve dans le package !!!!

// TODO : renommer cette classe en EventStoppableTrait pour que ce soit plus claire ????
trait StoppableTrait
{
    /**
     * @var bool
     */
    protected $propagation = false;

    /**
     * Is propagation stopped?
     * This will typically only be used by the Dispatcher to determine if the
     * previous listener halted propagation.
     *
     * @return bool
     *              True if the Event is complete and no further listeners should be called.
     *              False to continue calling listeners.
     */
    public function isPropagationStopped(): bool
    {
        return $this->propagation;
    }

    public function setPropagation(bool $propagation): self
    {
        $this->propagation = $propagation;

        return $this;
    }
}
