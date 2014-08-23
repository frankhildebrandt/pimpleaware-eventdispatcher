<?php

namespace FHild\Pimple\EventDispatcher;

use Pimple\Container;

class PimpleAwareEventDispatcher extends \Symfony\Component\EventDispatcher\EventDispatcher {

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
        foreach(parent::getListeners($eventName) as $listener) {
            if (is_string($listener)) {
                list($service, $method) = explode(":", $listener);
                $processedListeners[] = array($this->container[$service], $method);
            } else {
                $processedListeners[] = $listener;
            }
        }
        return $processedListeners;
    }

}