<?php

declare(strict_types=1);

namespace Chiron\Event\Test;

use Hyperf\Config\Config;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Event\Annotation\Listener as ListenerAnnotation;
use Chiron\Event\EventDispatcher;
use Chiron\Event\ListenerProvider;
use Chiron\Event\ListenerProviderFactory;
use Chiron\Event\Test\Event\Alpha;
use Chiron\Event\Test\Event\Beta;
use Chiron\Event\Test\Listener\AlphaListener;
use Chiron\Event\Test\Listener\BetaListener;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use SplPriorityQueue;

/**
 * @internal
 * @covers \Hyperf\Event\Annotation\Listener
 * @covers \Hyperf\Event\EventDispatcher
 * @covers \Hyperf\Event\ListenerProvider
 * @covers \Hyperf\Event\ListenerProviderFactory
 */
class ListenerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testInvokeListenerProvider()
    {
        $listenerProvider = new ListenerProvider();
        $this->assertInstanceOf(ListenerProviderInterface::class, $listenerProvider);
    }

    public function testInvokeListenerProviderWithListeners()
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

    public function testListenerProcess()
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
