<?php

namespace Engine\Http\Observer\HttpExchange;

use Engine\Http\Event\HttpExchangeEvent;
use Engine\Http\UseCase\EmitResponse;
use Engine\Rx\Abstraction\Observer;
use Engine\Rx\Service\ObserverStub;
use Throwable;

/**
 * Class ResponseEmitter.
 *
 * Emits the HTTP response when the HttpExchangeEvent are triggered by observable.
 */
class ResponseEmitter implements Observer
{
    use ObserverStub;

    /**
     * @var EmitResponse
     */
    private $emitResponse;

    /**
     * ResponseEmitter constructor.
     *
     * @param EmitResponse $emitResponse
     */
    public function __construct(EmitResponse $emitResponse)
    {
        $this->emitResponse = $emitResponse;
    }

    /**
     * Emits the HTTP response when the HttpExchangeEvent are triggered by observable.
     *
     * @param HttpExchangeEvent $event
     */
    public function onNext($event)
    {
        $this->emitResponse->execute($event->getResponse());
    }

    /**
     * Emits the HTTP response when an error occurred while processing the observers.
     *
     * @param Throwable         $throwable
     * @param HttpExchangeEvent $event
     */
    public function onError(Throwable $throwable, $event)
    {
        $this->emitResponse->execute($event->getResponse());
    }
}
