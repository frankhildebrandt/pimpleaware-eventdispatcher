<?php

namespace FHild\Pimple\EventDispatcherTests;

use FHild\Pimple\EventDispatcher\PimpleAwareEventDispatcher;
use Pimple\Container;
use Symfony\Component\EventDispatcher\Event;

class PimpleAwareEventDispatcherTest extends \PHPUnit_Framework_TestCase
{

    public function testEventDispatcher_uses_pimple_service()
    {
        $container = new Container();
        $container['testService'] = $this;

        $dispatcher = new PimpleAwareEventDispatcher($container);
        $dispatcher->addListener("test", "testService:onTest");
        $event = $dispatcher->dispatch("test");

        $this->assertTrue($event->called);
    }

    public function testEventDispatcher_uses_pimple_function_service()
    {
        $container = new Container();
        $container['testService'] = $container->protect(
            function (Event $event) {
                $event->called = true;
            }
        );

        $dispatcher = new PimpleAwareEventDispatcher($container);
        $dispatcher->addListener("test", "testService");
        $event = $dispatcher->dispatch("test");

        $this->assertTrue($event->called);
    }

    public function onTest(Event $event)
    {
        $event->called = true;
    }


}