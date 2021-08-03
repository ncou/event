<?php

declare(strict_types=1);

namespace Chiron\Event\Test\Event;

use Chiron\Event\StoppableTrait;
use Psr\EventDispatcher\StoppableEventInterface;

final class Alpha implements StoppableEventInterface
{
    use StoppableTrait;
}
