<?php

namespace Engine\Rx\Abstraction;

use Throwable;

/**
 * Interface Observer.
 */
interface Observer
{
    public function onNext($event);

    public function onError(Throwable $throwable, $event);

    public function onComplete();
}
