<?php

namespace Engine\FileManager\Action;

use Engine\FileManager\Persistence\Exception\NotFoundException;
use Engine\FileManager\UseCase\File\ReadFile;
use Engine\FileManager\Value\FileId;
use Engine\Http\Event\HttpExchangeEvent;
use Engine\Http\Value\HttpStatus;
use Engine\Rx\Abstraction\Observer;
use Engine\Rx\Service\ObserverStub;

/**
 * Class Read.
 *
 * Reads file by id.
 */
class Read implements Observer
{
    use ObserverStub;

    /**
     * @var ReadFile
     */
    private $readFile;

    /**
     * Read constructor.
     *
     * @param ReadFile $readFile
     */
    public function __construct(ReadFile $readFile)
    {
        $this->readFile = $readFile;
    }

    /**
     * Reads file by id.
     *
     * @param HttpExchangeEvent $event
     */
    public function onNext($event)
    {
        $id = new FileId($event->getRequest()->getBodyParam('id'));

        try {
            $file = $this->readFile->execute($id, false);
            $event->getResponse()->setBody($file);
        } catch (NotFoundException $exception) {
            $event->getResponse()->setStatus(new HttpStatus(404, $exception->getMessage()));
        }
    }
}
