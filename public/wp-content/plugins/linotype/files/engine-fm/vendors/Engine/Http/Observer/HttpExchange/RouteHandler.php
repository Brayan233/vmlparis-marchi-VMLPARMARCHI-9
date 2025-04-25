<?php

namespace Engine\Http\Observer\HttpExchange;

use Engine\Http\Abstraction\RouteProvider;
use Engine\Http\Event\HttpExchangeEvent;
use Engine\Rx\Abstraction\Observer;
use Engine\Rx\Service\ObserverStub;
use Throwable;

/**
 * Class RouteHandler.
 *
 * Manages the launch of the corresponding routing action when the HttpExchangeEvent are triggered by observable.
 * The action should implement Observer interface.
 */
class RouteHandler implements Observer
{
    use ObserverStub;

    /**
     * @var RouteProvider
     */
    private $routeProvider;

    /**
     * @var Observer|null
     */
    private $action;

    public function __construct(RouteProvider $routeProvider)
    {
        $this->routeProvider = $routeProvider;
    }

    /**
     * Runs a corresponding routing action when the HttpExchangeEvent are triggered by observable.
     * The action should implement Observer interface.
     *
     * @param HttpExchangeEvent $event
     */
    public function onNext($event)
    {
        $this->action = $this->routeProvider->getAction($event->getRequest());
        $this->action->onNext($event);
    }

    /**
     * Runs error handler on corresponding routing action.
     *
     * @param Throwable         $throwable
     * @param HttpExchangeEvent $event
     */
    public function onError(Throwable $throwable, $event)
    {
        if (null !== $this->action) {
            $this->action->onError($throwable, $event);
        }
    }

    /**
     * Runs completion handler on corresponding routing action.
     */
    public function onComplete()
    {
        if (null !== $this->action) {
            $this->action->onComplete();
        }
    }
}
