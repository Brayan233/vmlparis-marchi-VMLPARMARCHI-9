<?php

namespace Engine\FileManager\UseCase\HTTP;

use Engine\FileManager\View\Abstraction\Factory;
use Engine\Http\Value\HttpResponse;

/**
 * Class SerializeJsonResponse.
 *
 * Handles JSON response.
 */
class SerializeJsonResponse
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * SerializeJsonResponse constructor.
     *
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Handles JSON response.
     * Sets response body to JSON string.
     *
     * @param HttpResponse $response
     */
    public function execute(HttpResponse $response)
    {
        if ($response->containsInHeader('Content-Type', 'application/json')) {
            $response->setBody(json_encode(
                $this->factory->createView($response->getBody()),
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            ));
        }
    }
}
