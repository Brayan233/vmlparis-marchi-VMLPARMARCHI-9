<?php

namespace Engine\FileManager\UseCase\HTTP;

use Engine\Http\Value\HttpResponse;
use Engine\Http\Value\HttpStatus;
use Engine\Lang\Service\ErrorToExceptionTranslator;
use Throwable;

/**
 * Class HandleError.
 *
 * Handles errors.
 */
class HandleError
{
    /**
     * HandleError constructor.
     *
     * @param ErrorToExceptionTranslator $errorToExceptionTranslator
     */
    public function __construct(ErrorToExceptionTranslator $errorToExceptionTranslator)
    {
        $errorToExceptionTranslator->replaceErrorMessage('Something wrong on server side.');
        $errorToExceptionTranslator->enable();
    }

    /**
     * Sets HTTP status and phrase corresponding to the error.
     *
     * @param Throwable $throwable
     * @param HttpResponse $response
     */
    public function execute(Throwable $throwable, HttpResponse $response)
    {
        $response->setStatus(new HttpStatus(500, $throwable->getMessage()));
    }
}
