<?php

namespace Engine\Rx\Service;

use Engine\Rx\Abstraction\EntityEventManager;
use Engine\Rx\Abstraction\EventEntity;
use Engine\Rx\Abstraction\EventProvider;

/**
 * Entity event manager implementation.
 *
 * Class RxEntityEventManager
 */
class RxEntityEventManager implements EntityEventManager
{
    /**
     * @var EventProvider
     */
    private $eventProvider;

    /**
     * RxEntityEventManager constructor.
     *
     * @param EventProvider $eventProvider
     */
    public function __construct(EventProvider $eventProvider)
    {
        $this->eventProvider = $eventProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function process(EventEntity $entity)
    {
        foreach ($entity->getEvents() as $event) {
            $this->eventProvider->trigger($event);
        }
        $this->clear($entity);
    }

    /**
     * {@inheritdoc}
     */
    public function clear(EventEntity $entity)
    {
        $entity->clearEvents();
    }
}
