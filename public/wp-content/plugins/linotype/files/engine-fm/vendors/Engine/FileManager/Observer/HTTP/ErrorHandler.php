<?php

namespace Engine\FileManager\Observer\HTTP;

use Engine\FileManager\UseCase\HTTP\HandleError;
use Engine\Http\Event\HttpExchangeEvent;
use Engine\Rx\Abstraction\Observer;
use Engine\Rx\Service\ObserverStub;
use Throwable;

/**
 * Class ErrorHandler.
 *
 * Handles errors which occurs on HttpExchangeEvent process.
 */
class ErrorHandler implements Observer
{
    use ObserverStub;

    /**
     * @var HandleError
     */
    private $handleError;

    public function __construct(HandleError $handleError)
    {
        $this->handleError = $handleError;
    }

    /**
     * Handles errors which occurs on HttpExchangeEvent process.
     *
     * @param Throwable     $throwable
     * @param HttpExchangeEvent $event
     */
    public function onError(Throwable $throwable, $event)
    {
        $this->handleError->execute($throwable, $event->getResponse());
    }
}
