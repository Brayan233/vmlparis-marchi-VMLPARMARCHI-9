<?php

namespace Engine\FileManager\Observer\HTTP;

use Engine\FileManager\UseCase\HTTP\ParseJsonRequest;
use Engine\Http\Event\HttpExchangeEvent;
use Engine\Rx\Abstraction\Observer;
use Engine\Rx\Service\ObserverStub;

/**
 * Class JsonRequestParser
 *
 * Handles JSON request on HttpExchangeEvent process.
 */
class JsonRequestParser implements Observer
{
    use ObserverStub;

    /**
     * @var ParseJsonRequest
     */
    private $handleJsonRequest;

    /**
     * JsonRequestParser constructor.
     *
     * @param ParseJsonRequest $parseJSONRequest
     */
    public function __construct(ParseJsonRequest $parseJSONRequest)
    {
        $this->handleJsonRequest = $parseJSONRequest;
    }

    /**
     * Handles JSON request on HttpExchangeEvent process.
     *
     * @param HttpExchangeEvent $event
     */
    public function onNext($event)
    {
        $this->handleJsonRequest->execute($event->getRequest(), $event->getResponse());
    }
}
