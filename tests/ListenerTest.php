<?php

declare(strict_types=1);

namespace Chiron\Event\Test;

use Chiron\Event\EventDispatcher;
use Chiron\Event\ListenerProvider;
use Chiron\Event\Test\Event\Alpha;
use Chiron\Event\Test\Listener\AlphaListener;
use Chiron\Event\Test\Listener\BetaListener;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\ListenerProviderInterface;

class ListenerTest extends TestCase
{
    public function testInvokeListenerProvider(): void
    {
        $listenerProvider = new ListenerProvider();
        $this->assertInstanceOf(ListenerProviderInterface::class, $listenerProvider);
    }

    public function testInvokeListenerProviderWithListeners(): void
    {
        $listenerProvider = new ListenerProvider();

        $callable1 = [new AlphaListener(), 'process'];
        $callable2 = [new BetaListener(), 'process'];

        $listenerProvider->attach(Alpha::class, $callable1);
        $listenerProvider->attach(Alpha::class, $callable2);

        $listeners = iterator_to_array($listenerProvider->getListenersForEvent(new Alpha()));

        $this->assertEquals($listeners[0], $callable1);
        $this->assertEquals($listeners[1], $callable2);
    }

    public function testListenerProcess(): void
    {
        $listener = new AlphaListener();

        $listenerProvider = new ListenerProvider();
        $listenerProvider->add($listener);
        $this->assertSame(1, $listener->value);

        $dispatcher = new EventDispatcher($listenerProvider);
        $dispatcher->dispatch(new Alpha());
        $this->assertSame(2, $listener->value);
    }
}
