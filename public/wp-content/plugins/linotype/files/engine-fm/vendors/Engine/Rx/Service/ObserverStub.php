<?php

namespace Engine\Rx\Service;

use Throwable;

/**
 * Trait ObserverStub.
 *
 * Facilitates the implementation of the observer.
 */
trait ObserverStub
{
    /**
     * @param $event
     */
    public function onNext($event)
    {
    }

    /**
     * @param Throwable $throwable
     * @param $event
     */
    public function onError(Throwable $throwable, $event)
    {
    }

    public function onComplete()
    {
    }
}
