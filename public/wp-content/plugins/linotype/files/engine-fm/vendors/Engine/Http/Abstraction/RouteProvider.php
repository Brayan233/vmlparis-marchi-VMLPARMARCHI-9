<?php

namespace Engine\Http\Abstraction;

use Engine\Http\Value\HttpRequest;
use Engine\Rx\Abstraction\Observer;

/**
 * Interface RouteProvider.
 */
interface RouteProvider
{
    /**
     * Sets the action corresponding to the route.
     *
     * @param string $route
     * @param string $action
     *
     * @return RouteProvider
     */
    public function when(string $route, string $action): RouteProvider;

    /**
     * Sets the action by default.
     *
     * @param string $action
     *
     * @return RouteProvider
     */
    public function otherwise(string $action): RouteProvider;

    /**
     * Returns the action corresponding to the request.
     *
     * @param HttpRequest $request
     *
     * @return Observer
     */
    public function getAction(HttpRequest $request): Observer;
}
