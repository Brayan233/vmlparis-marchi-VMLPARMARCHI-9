<?php

use Engine\FileManager\Observer\HTTP\ErrorHandler;
use Engine\FileManager\Observer\HTTP\JsonRequestParser;
use Engine\FileManager\Observer\HTTP\JsonResponseSerializer;
use Engine\Http\Event\HttpExchangeEvent;
use Engine\Http\Observer\HttpExchange\ResponseEmitter;
use Engine\Http\Observer\HttpExchange\RouteHandler;

return [
    HttpExchangeEvent::class => [
        ErrorHandler::class,
        JsonRequestParser::class,
        RouteHandler::class,
        JsonResponseSerializer::class,
        ResponseEmitter::class,
    ],
];
