<?php

namespace Engine\FileManager\Action;

use Engine\FileManager\UseCase\File\CreateHyperlink as Service;
use Engine\FileManager\Value\FileId;
use Engine\FileManager\Value\FileName;
use Engine\Http\Event\HttpExchangeEvent;
use Engine\Http\Value\Url;
use Engine\Rx\Abstraction\Observer;
use Engine\Rx\Service\ObserverStub;

/**
 * Class CreateHyperlink.
 *
 * Creates hyperlink in directory with parentId.
 */
class CreateHyperlink implements Observer
{
    use ObserverStub;

    /**
     * @var Service
     */
    private $createHyperlink;

    /**
     * CreateHyperlink constructor.
     *
     * @param Service $createHyperlink
     */
    public function __construct(Service $createHyperlink)
    {
        $this->createHyperlink = $createHyperlink;
    }

    /**
     * Creates hyperlink in directory with parentId.
     *
     * @param HttpExchangeEvent $event
     */
    public function onNext($event)
    {
        $parentId = new FileId($event->getRequest()->getBodyParam('parentId'));
        $name = new FileName($event->getRequest()->getBodyParam('name'));
        $url = new Url($event->getRequest()->getBodyParam('url'));

        $file = $this->createHyperlink->execute($name, $parentId, $url);

        $event->getResponse()->setBody($file);
    }
}
