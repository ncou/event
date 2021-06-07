<?php

declare(strict_types=1);

namespace Chiron\Event\Test;

use Hyperf\Config\Config;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Chiron\Event\EventDispatcher;
use Chiron\Event\EventDispatcherFactory;
use Chiron\Event\ListenerProvider;
use Hyperf\Framework\Logger\StdoutLogger;
use Chiron\Event\Test\Event\Alpha;
use Chiron\Event\Test\Event\PriorityEvent;
use Chiron\Event\Test\Listener\AlphaListener;
use Chiron\Event\Test\Listener\BetaListener;
use Chiron\Event\Test\Listener\PriorityListener;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use ReflectionClass;

/**
 * @internal
 * @covers \Hyperf\Event\EventDispatcher
 */
class EventDispatcherTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testInvokeDispatcher()
    {
        $listeners = Mockery::mock(ListenerProviderInterface::class);
        $this->assertInstanceOf(EventDispatcherInterface::class, new EventDispatcher($listeners));
    }

    public function testInvokeDispatcherWithStdoutLogger()
    {
        $listeners = Mockery::mock(ListenerProviderInterface::class);
        $logger = Mockery::mock(StdoutLoggerInterface::class);
        $this->assertInstanceOf(EventDispatcherInterface::class, $instance = new EventDispatcher($listeners, $logger));
        $reflectionClass = new ReflectionClass($instance);
        $loggerProperty = $reflectionClass->getProperty('logger');
        $loggerProperty->setAccessible(true);
        $this->assertInstanceOf(StdoutLoggerInterface::class, $loggerProperty->getValue($instance));
    }

    public function testStoppable()
    {
        $listeners = new ListenerProvider();
        $listeners->on(Alpha::class, [$alphaListener = new AlphaListener(), 'process']);
        $listeners->on(Alpha::class, [$betaListener = new BetaListener(), 'process']);
        $dispatcher = new EventDispatcher($listeners);
        $dispatcher->dispatch((new Alpha())->setPropagation(true));
        $this->assertSame(2, $alphaListener->value);
        $this->assertSame(1, $betaListener->value);
    }

    public function testLoggerDump()
    {
        $logger = Mockery::mock(StdoutLoggerInterface::class);
        $logger->shouldReceive('debug');
        $listenerProvider = new ListenerProvider();
        $listenerProvider->on(Alpha::class, [new AlphaListener(), 'process']);
        $dispatcher = new EventDispatcher($listenerProvider, $logger);
        $dispatcher->dispatch(new Alpha());
    }

    public function testListenersWithPriority()
    {
        PriorityEvent::$result = [];
        $listenerProvider = new ListenerProvider();
        $listenerProvider->on(PriorityEvent::class, [new PriorityListener(1), 'process'], 1);
        $listenerProvider->on(PriorityEvent::class, [new PriorityListener(2), 'process'], 3);
        $listenerProvider->on(PriorityEvent::class, [new PriorityListener(3), 'process'], 2);
        $listenerProvider->on(PriorityEvent::class, [new PriorityListener(4), 'process'], 0);
        $listenerProvider->on(PriorityEvent::class, [new PriorityListener(5), 'process'], 99);
        $listenerProvider->on(PriorityEvent::class, [new PriorityListener(6), 'process'], -99);

        $dispatcher = new EventDispatcher($listenerProvider);
        $dispatcher->dispatch(new PriorityEvent());

        $this->assertSame([5, 2, 3, 1, 4, 6], PriorityEvent::$result);
    }
}
