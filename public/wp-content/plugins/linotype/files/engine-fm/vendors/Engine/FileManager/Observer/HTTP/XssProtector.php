<?php

namespace Engine\FileManager\Observer\HTTP;

use Engine\FileManager\UseCase\HTTP\FilterParamsAgainstXss;
use Engine\Http\Event\HttpExchangeEvent;
use Engine\Rx\Abstraction\Observer;
use Engine\Rx\Service\ObserverStub;

/**
 * Class XssProtector.
 *
 * Used for XSS protection.
 */
class XssProtector implements Observer
{
    use ObserverStub;

    private $filterParamsAgainstXss;

    /**
     * XssProtector constructor.
     *
     * @param FilterParamsAgainstXss $filterParamsAgainstXss
     */
    public function __construct(FilterParamsAgainstXss $filterParamsAgainstXss)
    {
        $this->filterParamsAgainstXss = $filterParamsAgainstXss;
    }

    /**
     * Filters HTTP params against XSS attack.
     *
     * @param HttpExchangeEvent $event
     */
    public function onNext($event)
    {
        $this->filterParamsAgainstXss->execute($event->getRequest());
    }
}
