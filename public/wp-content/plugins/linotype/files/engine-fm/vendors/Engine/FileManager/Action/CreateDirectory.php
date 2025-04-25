<?php

namespace Engine\FileManager\Action;

use Engine\FileManager\UseCase\File\CreateDirectory as Service;
use Engine\FileManager\Value\FileId;
use Engine\FileManager\Value\FileName;
use Engine\Http\Event\HttpExchangeEvent;
use Engine\Rx\Abstraction\Observer;
use Engine\Rx\Service\ObserverStub;

/**
 * Class CreateDirectory.
 *
 * Creates directory in directory with parentId.
 */
class CreateDirectory implements Observer
{
    use ObserverStub;

    /**
     * @var Service
     */
    private $createDirectory;

    /**
     * CreateDirectory constructor.
     *
     * @param Service $createDirectory
     */
    public function __construct(Service $createDirectory)
    {
        $this->createDirectory = $createDirectory;
    }

    /**
     * Creates directory in directory with parentId.
     *
     * @param HttpExchangeEvent $event
     */
    public function onNext($event)
    {
        $parentId = new FileId($event->getRequest()->getBodyParam('parentId'));
        $name = new FileName($event->getRequest()->getBodyParam('name'), true);

        $file = $this->createDirectory->execute($name, $parentId);

        $event->getResponse()->setBody($file);
    }
}
