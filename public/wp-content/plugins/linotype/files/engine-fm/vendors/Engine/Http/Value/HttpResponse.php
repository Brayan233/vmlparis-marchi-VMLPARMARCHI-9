<?php

namespace Engine\Http\Value;

/**
 * Class HttpResponse.
 *
 * HTTP response implementation.
 */
class HttpResponse
{
    use HttpMessage;

    /**
     * @var HttpStatus
     */
    private $status;

    /**
     * @return HttpStatus
     */
    public function getStatus(): HttpStatus
    {
        return $this->status ?? new HttpStatus(200);
    }

    /**
     * @param HttpStatus $status
     *
     * @return HttpResponse
     */
    public function setStatus(HttpStatus $status): HttpResponse
    {
        $this->status = $status;

        return $this;
    }
}
