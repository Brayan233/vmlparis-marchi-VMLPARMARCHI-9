<?php

namespace Engine\FileManager\UseCase\HTTP;

use Engine\Http\Value\HttpRequest;

/**
 * Filters HTTP params against XSS attack.
 *
 * Class FilterParamsAgainstXss.
 */
class FilterParamsAgainstXss
{
    /**
     * Filters HTTP params against XSS attack.
     *
     * @param HttpRequest $request
     */
    public function execute(HttpRequest $request)
    {
        foreach ($request->getBodyParams() as $name => $value) {
            $request->setBodyParam($name, htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
        }
    }
}
