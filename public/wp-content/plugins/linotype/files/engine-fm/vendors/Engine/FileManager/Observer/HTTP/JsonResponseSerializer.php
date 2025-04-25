<?php

namespace Engine\FileManager\Observer\HTTP;

use Engine\FileManager\UseCase\HTTP\SerializeJsonResponse;
use Engine\Http\Event\HttpExchangeEvent;
use Engine\Rx\Abstraction\Observer;
use Engine\Rx\Service\ObserverStub;

/**
 * Class JsonResponseSerializer.
 *
 * Handles JSON response on HttpExchangeEvent process.
 */
class JsonResponseSerializer implements Observer
{
    use ObserverStub;

    /**
     * @var SerializeJsonResponse
     */
    private $serializeJsonResponse;

    /**
     * JSONResponseHandler constructor.
     */
    public function __construct(SerializeJsonResponse $handleJSONResponse)
    {
        $this->serializeJsonResponse = $handleJSONResponse;
    }

    /**
     * Handles JSON response on HttpExchangeEvent process.
     *
     * @param HttpExchangeEvent $event
     */
    public function onNext($event)
    {
        $this->serializeJsonResponse->execute($event->getResponse());
    }
}
