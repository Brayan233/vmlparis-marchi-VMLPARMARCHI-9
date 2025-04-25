<?php

namespace Engine\FileManager\UseCase\HTTP;

use DomainException;
use Engine\Http\Value\HttpRequest;
use Engine\Utility\Abstraction\Config;

/**
 * Class CheckCsrfToken.
 *
 * Checks if CSRF token is valid.
 */
class CheckCsrfToken
{
    /**
     * @var Config
     */
    private $config;

    /**
     * CheckCsrfToken constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Checks if CSRF token is valid.
     *
     * @param HttpRequest $request
     */
    public function execute(HttpRequest $request)
    {
        $csrfTokenName = $this->config->get('csrfTokenName');
        $knownToken = $_SESSION[$csrfTokenName] ?? null;
        $userToken = 'GET' !== $request->getMethod() ?
            $request->getHeaderLine('CSRF-Token') : $request->getQueryParam('CSRF-Token');

        echo $userToken;

        if (null !== $csrfTokenName &&
            (null === $userToken || null === $knownToken || !hash_equals($knownToken, $userToken))
        ) {
            throw new DomainException('Invalid token was provided');
        }
    }
}
