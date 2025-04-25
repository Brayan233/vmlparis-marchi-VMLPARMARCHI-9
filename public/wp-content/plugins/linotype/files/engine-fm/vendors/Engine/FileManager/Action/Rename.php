<?php

namespace Engine\FileManager\Action;

use Engine\FileManager\UseCase\File\RenameFile;
use Engine\FileManager\Value\FileId;
use Engine\FileManager\Value\FileName;
use Engine\Http\Event\HttpExchangeEvent;
use Engine\Rx\Abstraction\Observer;
use Engine\Rx\Service\ObserverStub;

/**
 * Class Rename.
 *
 * Renames file by id.
 */
class Rename implements Observer
{
    use ObserverStub;

    /**
     * @var RenameFile
     */
    private $renameFile;

    /**
     * Read constructor.
     *
     * @param RenameFile $renameFile
     */
    public function __construct(RenameFile $renameFile)
    {
        $this->renameFile = $renameFile;
    }

    /**
     * Renames file by id.
     *
     * @param HttpExchangeEvent $event
     */
    public function onNext($event)
    {
        $id = new FileId($event->getRequest()->getBodyParam('id'));
        $name = new FileName($event->getRequest()->getBodyParam('name'));

        $file = $this->renameFile->execute($id, $name);

        $event->getResponse()->setBody($file);
    }
}
