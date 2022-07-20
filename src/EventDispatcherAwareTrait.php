<?php

declare(strict_types=1);

namespace Chiron\Event;

use Psr\EventDispatcher\EventDispatcherInterface;
use UnexpectedValueException;

/**
 * Defines the trait for a EventDispatcher Aware Class.
 */
// TODO : classe + interface à déplacer dans le package chiron/core ???? + modifier l'exception levée par un ImproperlyConfiguredException !!!
// TODO : ajouter un méthode pour dispatcher un event du genre dispatchEvent($object); + actualiser l'interface.
// TODO : renommer en EventCapableTrait
trait EventDispatcherAwareTrait
{
    /** @var ?EventDispatcherInterface */
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
        // TODO : lever une exception si on n'a pas implémenté l'interface ContainerAwareInterface car le return $this sera en conflit avec le return typehint !!!
        //https://github.com/thephpleague/container/blob/4.x/src/ContainerAwareTrait.php

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
