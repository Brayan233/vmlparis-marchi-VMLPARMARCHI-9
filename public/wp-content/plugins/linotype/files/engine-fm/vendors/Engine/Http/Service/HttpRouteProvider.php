<?php

namespace Engine\Http\Service;

use Engine\Di\Abstraction\Injector;
use Engine\Http\Abstraction\RouteProvider;
use Engine\Http\Value\HttpRequest;
use Engine\Rx\Abstraction\Observer;

/**
 * Class HttpRouteProvider.
 *
 * Http route provider implementation.
 */
class HttpRouteProvider implements RouteProvider
{
    /**
     * @var Injector
     */
    private $injector;

    /**
     * @var array
     */
    private $routes;

    /**
     * @var string
     */
    private $defaultRoute;

    /**
     * HttpRouteProvider constructor.
     *
     * @param Injector $injector
     * @param array    $routes
     */
    public function __construct(Injector $injector, array $routes)
    {
        $this->injector = $injector;
        $this->routes = $routes;

        foreach ($routes as $route => $action) {
            if (null !== $route) {
                $this->when($route, $action);
            } else {
                $this->otherwise($action);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function when(string $route, string $action): RouteProvider
    {
        $this->routes[$route] = $action;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function otherwise(string $action): RouteProvider
    {
        $this->defaultRoute = $action;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAction(HttpRequest $request): Observer
    {
        $class = $this->routes[$request->getMethod().':'.$request->getUrl()->getPath()] ??
            $this->routes[$request->getUrl()->getPath()] ??
            $this->defaultRoute;

        $action = $this->injector->createObject($class);

        return $action;
    }
}
