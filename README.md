# PimpleAwareEventDispatcher

The PimpleAwareEventDispatcher extends the original Symfony2 EventDispatcher with the ability to consume Pimple 
Services as EventListener. This gives your application the ability to lazy-instantiate its EventListeners.

## Usage

```
use Fhild\Pimple\EventDispatcher\PimpleAwareEventDispatcher

$container = new Container();
$container['someservice'] = function() {
    return new SomeService();
};

$dispatcher = new PimpleAwareEventDispatcher($container);
$dispatcher->addListener("my.event", "someservice:onTest");

$event = $dispatcher->dispatch("my.event");

```