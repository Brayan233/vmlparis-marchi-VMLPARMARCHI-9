<?php

namespace Engine\FileManager\Action;

use Engine\FileManager\UseCase\File\CopyFiles;
use Engine\FileManager\Value\FileId;
use Engine\Http\Event\HttpExchangeEvent;
use Engine\Rx\Abstraction\Observer;
use Engine\Rx\Service\ObserverStub;

/**
 * Class Copy.
 *
 * Copies files by list ids to directory with parentId.
 */
class Copy implements Observer
{
    use ObserverStub;

    /**
     * @var CopyFiles
     */
    private $copyFiles;

    /**
     * Copy constructor.
     *
     * @param CopyFiles $copyFiles
     */
    public function __construct(CopyFiles $copyFiles)
    {
        $this->copyFiles = $copyFiles;
    }

    /**
     * Copies files by list ids to directory with parentId.
     *
     * @param HttpExchangeEvent $event
     */
    public function onNext($event)
    {
        $parentId = new FileId($event->getRequest()->getBodyParam('parentId'));
        $ids = $event->getRequest()->getBodyParam('id');

        if (!is_array($ids)) {
            $ids = [$ids];
        }

        foreach ($ids as &$id) {
            $id = new FileId($id);
        }

        $files = $this->copyFiles->execute($parentId, ...$ids);

        $event->getResponse()->setBody($files);
    }
}
