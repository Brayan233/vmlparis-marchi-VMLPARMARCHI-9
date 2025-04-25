<?php

namespace Engine\Rx\Service;

use Engine\Rx\Abstraction\Observer;
use SplObjectStorage;
use Throwable;

/**
 * Class Observable.
 *
 * Event observable implementation.
 */
class Observable
{
    /**
     * @var Observer[]
     */
    private $storage;

    /**
     * Observable constructor.
     */
    public function __construct()
    {
        $this->storage = new SplObjectStorage();
    }

    /**
     * @param Observer $observer
     *
     * @return $this
     */
    public function addObserver(Observer $observer): self
    {
        $this->storage->attach($observer);

        return $this;
    }

    /**
     * @param Observer $observer
     *
     * @return $this
     */
    public function removeObserver(Observer $observer): self
    {
        $this->storage->detach($observer);

        return $this;
    }

    /**
     * Notifies observers about event.
     *
     * @param $event
     */
    public function notifyObservers($event)
    {
        try {
            foreach ($this->storage as $observer) {
                $observer->onNext($event);
            }
            foreach ($this->storage as $observer) {
                $observer->onComplete();
            }
        } catch (Throwable $throwable) {
            foreach ($this->storage as $observer) {
                $observer->onError($throwable, $event);
            }
        }
    }
}
