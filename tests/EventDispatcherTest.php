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

// TODO : utiliser cette classe pour tester notamment le spoofing (je pense que ca permet de vérifier que le Event est immutable !!!) + le test sur le Stoppable est beaucoup mieux fait. : https://github.com/yiisoft/event-dispatcher/blob/41b7ef783a4dc23c71230753726b0c6d3256c615/tests/Dispatcher/DispatcherTest.php

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

    public function testStoppable()
    {
        $listeners = new ListenerProvider();
        $listeners->add(Alpha::class, [$alphaListener = new AlphaListener(), 'process']);
        $listeners->add(Alpha::class, [$betaListener = new BetaListener(), 'process']);
        $dispatcher = new EventDispatcher($listeners);
        $dispatcher->dispatch((new Alpha())->setPropagation(true));
        $this->assertSame(1, $alphaListener->value);
        $this->assertSame(1, $betaListener->value);
    }

    public function testListenersWithPriority()
    {
        PriorityEvent::$result = [];
        $listenerProvider = new ListenerProvider();
        $listenerProvider->add(PriorityEvent::class, [new PriorityListener(1), 'process'], 1);
        $listenerProvider->add(PriorityEvent::class, [new PriorityListener(2), 'process'], 3);
        $listenerProvider->add(PriorityEvent::class, [new PriorityListener(3), 'process'], 2);
        $listenerProvider->add(PriorityEvent::class, [new PriorityListener(4), 'process'], 0);
        $listenerProvider->add(PriorityEvent::class, [new PriorityListener(5), 'process'], 99);
        $listenerProvider->add(PriorityEvent::class, [new PriorityListener(6), 'process'], -99);

        $dispatcher = new EventDispatcher($listenerProvider);
        $dispatcher->dispatch(new PriorityEvent());

        $this->assertSame([5, 2, 3, 1, 4, 6], PriorityEvent::$result);
    }
}
