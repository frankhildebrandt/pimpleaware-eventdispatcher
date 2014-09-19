<?php

namespace FHild\Pimple\EventDispatcherTests;

use FHild\Pimple\EventDispatcher\PimpleAwareEventDispatcher;
use Pimple\Container;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PimpleAwareEventDispatcherTest extends \PHPUnit_Framework_TestCase implements EventSubscriberInterface
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

    public function testEventDispatcher_subscriberService_uses_pimple_service()
    {
        $container = new Container();
        $container['testService'] = $this;

        $dispatcher = new PimpleAwareEventDispatcher($container);
        $dispatcher->addSubscriberService(self::getSubscribedEvents(), "testService");
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


    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            'test' => 'onTest'
        );
    }
}