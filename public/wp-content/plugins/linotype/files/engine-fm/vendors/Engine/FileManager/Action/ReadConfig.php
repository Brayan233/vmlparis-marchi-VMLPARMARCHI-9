<?php

namespace Engine\FileManager\Action;

use Engine\Http\Event\HttpExchangeEvent;
use Engine\Rx\Abstraction\Observer;
use Engine\Rx\Service\ObserverStub;
use Engine\FileManager\UseCase\Setting\ReadConfig as Service;

/**
 * Class ReadConfig.
 *
 * Reads an application config.
 */
class ReadConfig implements Observer
{
    use ObserverStub;

    /**
     * @var Service
     */
    private $readConfig;

    /**
     * ReadConfig constructor.
     *
     * @param Service $readConfig
     */
    public function __construct(Service $readConfig)
    {
        $this->readConfig = $readConfig;
    }

    /**
     * Reads an application config.
     *
     * @param HttpExchangeEvent $event
     */
    public function onNext($event)
    {
        $config = $this->readConfig->execute();

        $event->getResponse()->setBody($config->asArray());
    }
}
