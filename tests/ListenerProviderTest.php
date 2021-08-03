<?php

declare(strict_types=1);

namespace Chiron\Event\Test;

use Chiron\Event\ListenerProvider;
use Chiron\Event\Test\Event\Alpha;
use Chiron\Event\Test\Event\Beta;
use Chiron\Event\Test\Listener\AlphaListener;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ListenerProviderTest extends TestCase
{
    public function testListenNotExistEvent()
    {
        $provider = new ListenerProvider();
        $provider->add(Alpha::class, [new AlphaListener(), 'process']);
        $provider->add('NotExistEvent', [new AlphaListener(), 'process']);

        $it = $provider->getListenersForEvent(new Alpha());
        [$class, $method] = $it->current();
        $this->assertInstanceOf(AlphaListener::class, $class);
        $this->assertSame('process', $method);
        $this->assertNull($it->next());

        $it = $provider->getListenersForEvent(new Beta());
        $this->assertNull($it->current());
    }
}
