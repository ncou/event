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
        $this->assertTrue(is_array($listenerProvider->listeners));
    }

    public function testInvokeListenerProviderWithListeners()
    {
        $listenerProvider = new ListenerProvider();
        $this->assertInstanceOf(ListenerProviderInterface::class, $listenerProvider);

        $listenerProvider->add(Alpha::class, [new AlphaListener(), 'process']);
        $listenerProvider->add(Beta::class, [new BetaListener(), 'process']);
        $this->assertTrue(is_array($listenerProvider->listeners));
        $this->assertSame(2, count($listenerProvider->listeners));
        $this->assertInstanceOf(SplPriorityQueue::class, $listenerProvider->getListenersForEvent(new Alpha()));
    }

    public function testListenerProcess()
    {
        $listenerProvider = new ListenerProvider();
        $listenerProvider->add(Alpha::class, [$listener = new AlphaListener(), 'process']);
        $this->assertSame(1, $listener->value);

        $dispatcher = new EventDispatcher($listenerProvider);
        $dispatcher->dispatch(new Alpha());
        $this->assertSame(2, $listener->value);
    }
}
