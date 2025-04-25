<?php

namespace Engine\FileManager\UseCase\HTTP;

use Engine\Http\Value\HttpRequest;
use Engine\Http\Value\HttpResponse;

/**
 * Class ParseJsonRequest.
 *
 * Handles JSON request.
 */
class ParseJsonRequest
{
    /**
     * Handles JSON request.
     * Sets request body params from decoded request body.
     *
     * @param HttpRequest  $request
     * @param HttpResponse $response
     */
    public function execute(HttpRequest $request, HttpResponse $response)
    {
        $bodyParams = json_decode($request->getBody(), true) ?? [];
        $request->setBodyParams($bodyParams + $request->getBodyParams());
        $response->setHeader('Content-Type', 'application/json; charset=utf-8');
    }
}
