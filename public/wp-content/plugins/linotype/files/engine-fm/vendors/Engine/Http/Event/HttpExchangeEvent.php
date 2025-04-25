<?php

namespace Engine\Http\Event;

use Engine\Http\Value\HttpRequest;
use Engine\Http\Value\HttpResponse;

/**
 * Class HttpExchangeEvent.
 *
 * HTTP message exchange event.
 * It is a container for the HTTP request and HTTP response.
 */
class HttpExchangeEvent
{
    /**
     * @var HttpRequest
     */
    private $request;

    /**
     * @var HttpResponse
     */
    private $response;

    /**
     * HttpExchangeEvent constructor.
     *
     * @param HttpRequest  $request
     * @param HttpResponse $response
     */
    public function __construct(HttpRequest $request, HttpResponse $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return HttpRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return HttpResponse
     */
    public function getResponse()
    {
        return $this->response;
    }
}
