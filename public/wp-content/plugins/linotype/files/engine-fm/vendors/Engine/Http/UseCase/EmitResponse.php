<?php

namespace Engine\Http\UseCase;

use Engine\Http\Value\HttpResponse;

/**
 * Class EmitResponse.
 *
 * Emits the HTTP response.
 */
class EmitResponse
{
    /**
     * Emits the HTTP response.
     *
     * @param HttpResponse $response
     */
    public function execute(HttpResponse $response)
    {
        header(sprintf(
            'HTTP/%s %d %s',
            $response->getProtocolVersion(),
            $response->getStatus()->getCode(),
            $response->getStatus()->getReasonPhrase()
        ));

        foreach ($response->getHeaders() as $name => $values) {
            $name = str_replace(' ', '-', ucwords(str_replace('-', ' ', $name)));
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }

        echo $response->getBody();
    }
}
