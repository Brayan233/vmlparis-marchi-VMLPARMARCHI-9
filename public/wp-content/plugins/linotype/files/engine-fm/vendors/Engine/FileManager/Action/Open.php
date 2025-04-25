<?php

namespace Engine\FileManager\Action;

use Engine\FileManager\Entity\File;
use Engine\FileManager\Value\FileId;
use Engine\FileManager\UseCase\File\ReadFile;
use Engine\Http\Event\HttpExchangeEvent;
use Engine\Rx\Abstraction\Observer;
use Engine\Rx\Service\ObserverStub;

/**
 * Class Open.
 *
 * Opens file by id.
 */
class Open implements Observer
{
    use ObserverStub;

    /**
     * @var ReadFile
     */
    private $readFile;

    /**
     * @var File|null
     */
    private $file;

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
     * Opens file by id.
     *
     * @param HttpExchangeEvent $event
     */
    public function onNext($event)
    {
        $id = new FileId($event->getRequest()->getQueryParam('id'));

        $this->file = $this->readFile->execute($id);

        $event->getResponse()->setHeader('Content-Type', $this->file->getType());
    }

    public function onComplete()
    {
        if (ob_get_level()) {
            ob_end_clean();
        }

        readfile($this->file->getPath());
    }
}
