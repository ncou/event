<?php

declare(strict_types=1);

namespace Chiron\Event\Test;

use Chiron\Event\EventDispatcher;
use Chiron\Event\ListenerProvider;
use Chiron\Event\Test\Event\Alpha;
use Chiron\Event\Test\Listener\AlphaListener;
use Chiron\Event\Test\Listener\BetaListener;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

// TODO : utiliser cette classe pour tester notamment le spoofing (je pense que ca permet de vérifier que le Event est immutable !!!) + le test sur le Stoppable est beaucoup mieux fait. : https://github.com/yiisoft/event-dispatcher/blob/41b7ef783a4dc23c71230753726b0c6d3256c615/tests/Dispatcher/DispatcherTest.php

class EventDispatcherTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testInvokeDispatcher(): void
    {
        $listeners = Mockery::mock(ListenerProviderInterface::class);
        $this->assertInstanceOf(EventDispatcherInterface::class, new EventDispatcher($listeners));
    }

    public function testStoppable(): void
    {
        $listeners = new ListenerProvider();
        $listeners->attach(Alpha::class, [$alphaListener = new AlphaListener(), 'process']);
        $listeners->attach(Alpha::class, [$betaListener = new BetaListener(), 'process']);

        $dispatcher = new EventDispatcher($listeners);
        $event = new Alpha();
        $event->stopPropagation();
        $dispatcher->dispatch($event);

        $this->assertSame(1, $alphaListener->value);
        $this->assertSame(1, $betaListener->value);
    }
}
