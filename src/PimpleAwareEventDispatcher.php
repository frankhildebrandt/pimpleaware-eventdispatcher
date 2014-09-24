<?php

namespace FHild\Pimple\EventDispatcher;

use Pimple\Container;

class PimpleAwareEventDispatcher extends \Symfony\Component\EventDispatcher\EventDispatcher
{

    /**
     * @var \Pimple\Container
     */
    private $container;

    function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Adds multiple events for a subscriber service.
     *
     * Derived from
     * @see EventDispatcherInterface::addSubscriber
     *
     * For the event array format
     * @see EventSubscriberInterface::getSubscribedEvents
     *
     * @param array $events
     * @param $serviceId
     */
    public function addSubscriberService(array $events, $serviceId)
    {
        foreach ($events as $eventName => $params) {
            if (is_string($params)) {
                $this->addListener($eventName, $serviceId . ':' . $params);
            } elseif (is_string($params[0])) {
                $this->addListener($eventName, $serviceId . ':' . $params[0], isset($params[1]) ? $params[1] : 0);
            } else {
                foreach ($params as $listener) {
                    $this->addListener($eventName, $listener . ':' . $listener[0], isset($listener[1]) ? $listener[1] : 0);
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getListeners($eventName = null)
    {
        $processedListeners = array();
        foreach (parent::getListeners($eventName) as $listener) {
            if (is_string($listener)) {
                $processedListeners[] = $this->getCallable($listener);
            } else {
                $processedListeners[] = $listener;
            }
        }
        return $processedListeners;
    }

    /**
     * @param $listener
     * @return array
     */
    protected function getCallable($listener)
    {
        if (strpos($listener, ":") !== false) {
            list($service, $method) = explode(":", $listener);
            $callable = array($this->container[$service], $method);
            return $callable;
        }

        return $this->container[$listener];
    }

}