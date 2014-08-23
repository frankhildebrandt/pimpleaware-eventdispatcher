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