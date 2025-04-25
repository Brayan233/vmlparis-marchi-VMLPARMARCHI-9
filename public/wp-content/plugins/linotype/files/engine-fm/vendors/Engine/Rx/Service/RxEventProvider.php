<?php

namespace Engine\Rx\Service;

use Engine\Di\Abstraction\Injector;
use Engine\Rx\Abstraction\EventProvider;
use Engine\Rx\Abstraction\Observer;

/**
 * Class RxEventProvider.
 *
 * Event provider implementation.
 */
class RxEventProvider implements EventProvider
{
    /**
     * @var array
     */
    private $observables = [];

    /**
     * @var Injector
     */
    private $injector;

    /**
     * RxEventProvider constructor.
     *
     * @param Injector $injector
     * @param array    $events
     */
    public function __construct(Injector $injector, array $events)
    {
        $this->injector = $injector;

        foreach ($events as $event => $observers) {
            $this->on($event, $observers);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function trigger($event)
    {
        $this->getObservable(get_class($event))->notifyObservers($event);
    }

    /**
     * Adds the observers for the event.
     *
     * @param string $event
     * @param array  $observers
     */
    private function on(string $event, array $observers)
    {
        $this->observables[$event] = $observers;
    }

    /**
     * Returns the observable corresponding to the event.
     *
     * @param string $event
     *
     * @return Observable|null
     */
    private function getObservable(string $event)
    {
        if (!isset($this->observables[$event])) {
            return null;
        }

        if (is_object($this->observables[$event])) {
            return $this->observables[$event];
        }

        $observable = new Observable();
        foreach ($this->observables[$event] as $index => $observer) {
            /**
             * @var Observer
             */
            $observer = $this->injector->createObject($observer);
            $observable->addObserver($observer);
        }
        $this->observables[$event] = $observable;

        return $this->observables[$event];
    }
}
