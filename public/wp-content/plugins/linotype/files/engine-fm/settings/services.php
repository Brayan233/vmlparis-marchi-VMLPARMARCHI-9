<?php

use Engine\FileManager\Persistence\Contract\FileAdapter;
use Engine\FileManager\Persistence\Contract\FileFactory;
use Engine\FileManager\Persistence\Contract\FileRepository;
use Engine\FileManager\Persistence\IO\IOFileAdapter;
use Engine\FileManager\Persistence\IO\IOFileFactory;
use Engine\FileManager\Persistence\IO\IOFileRepository;
use Engine\FileManager\Setting\ArrayAppConfig;
use Engine\FileManager\Setting\Contract\AppConfig;
use Engine\FileManager\View\Abstraction\Factory;
use Engine\FileManager\View\ViewFactory;
use Engine\Http\Abstraction\RouteProvider;
use Engine\Http\Service\HttpRouteProvider;
use Engine\Rx\Abstraction\EventProvider;
use Engine\Rx\Service\RxEventProvider;

return [
    AppConfig::class => [
        ArrayAppConfig::class,
        'config' => require __DIR__.'/config.php',
    ],
    EventProvider::class => [
        RxEventProvider::class,
        'events' => require __DIR__.'/events.php',
    ],
    RouteProvider::class => [
        HttpRouteProvider::class,
        'routes' => require __DIR__.'/routes.php',
    ],
    Factory::class => [
        ViewFactory::class,
        'views' => require __DIR__.'/views.php',
    ],
    FileAdapter::class => IOFileAdapter::class,
    FileFactory::class => IOFileFactory::class,
    FileRepository::class => IOFileRepository::class,
];
