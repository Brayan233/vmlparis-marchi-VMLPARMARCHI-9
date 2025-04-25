<?php

namespace Engine\FileManager\Action;

use Engine\FileManager\UseCase\File\TrashFiles;
use Engine\FileManager\Value\FileId;
use Engine\Http\Event\HttpExchangeEvent;
use Engine\Rx\Abstraction\Observer;
use Engine\Rx\Service\ObserverStub;

/**
 * Class Trash.
 *
 * Puts the files to the trash directory.
 */
class Trash implements Observer
{
    use ObserverStub;

    /**
     * @var TrashFiles
     */
    private $trashFiles;

    /**
     * Trash constructor.
     *
     * @param TrashFiles $trashFiles
     */
    public function __construct(TrashFiles $trashFiles)
    {
        $this->trashFiles = $trashFiles;
    }

    /**
     * Puts the files to the trash directory.
     *
     * @param HttpExchangeEvent $event
     */
    public function onNext($event)
    {
        $ids = $event->getRequest()->getBodyParam('id');

        if (!is_array($ids)) {
            $ids = [$ids];
        }

        foreach ($ids as &$id) {
            $id = new FileId($id);
        }

        $files = $this->trashFiles->execute(...$ids);

        $event->getResponse()->setBody($files);
    }
}
