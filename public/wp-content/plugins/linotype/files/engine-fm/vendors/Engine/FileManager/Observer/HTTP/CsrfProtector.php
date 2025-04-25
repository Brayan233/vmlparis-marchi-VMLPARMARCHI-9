<?php

namespace Engine\FileManager\Observer\HTTP;

use Engine\FileManager\UseCase\HTTP\CheckCsrfToken;
use Engine\Http\Event\HttpExchangeEvent;
use Engine\Rx\Abstraction\Observer;
use Engine\Rx\Service\ObserverStub;
use Engine\Utility\Abstraction\Config;

/**
 * Class CSRFProtector.
 *
 * Used for CSRF protection.
 */
class CsrfProtector implements Observer
{
    use ObserverStub;

    /**
     * @var CheckCsrfToken
     */
    private $checkCSRFToken;

    /**
     * CsrfProtector constructor.
     *
     * @param CheckCsrfToken $checkCsrfToken
     */
    public function __construct(CheckCsrfToken $checkCsrfToken)
    {
        $this->checkCSRFToken = $checkCsrfToken;
    }

    /**
     * Checks if CSRF token is valid.
     *
     * @param HttpExchangeEvent $event
     */
    public function onNext($event)
    {
        $this->checkCSRFToken->execute($event->getRequest());
    }
}
