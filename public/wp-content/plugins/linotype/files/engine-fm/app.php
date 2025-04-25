<?php

use Engine\Di\Service\ReflectionInjector;
use Engine\Http\Event\HttpExchangeEvent;
use Engine\Http\Value\CgiRequest;
use Engine\Http\Value\HttpResponse;
use Engine\Rx\Abstraction\EventProvider;

require __DIR__.'/vendors/classloader.php';
require __DIR__.'/lib.php';

(new ReflectionInjector(require __DIR__.'/settings/services.php'))
    ->getService(EventProvider::class)
    ->trigger(new HttpExchangeEvent(new CgiRequest(), new HttpResponse()));
