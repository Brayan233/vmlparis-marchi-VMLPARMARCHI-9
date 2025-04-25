<?php

namespace Engine\Rx\Abstraction;

/**
 * Interface EventProvider.
 */
interface EventProvider
{
    /**
     * Trigger event.
     *
     * Notify observers about event.
     *
     * @param $event
     */
    public function trigger($event);
}
