<?php

namespace Engine\FileManager\Action;

use Engine\FileManager\UseCase\File\RemoveFiles;
use Engine\FileManager\Value\FileId;
use Engine\Http\Event\HttpExchangeEvent;
use Engine\Rx\Abstraction\Observer;
use Engine\Rx\Service\ObserverStub;

/**
 * Class Remove.
 *
 * Remove files by list of ids.
 */
class Remove implements Observer
{
    use ObserverStub;

    /**
     * @var RemoveFiles
     */
    private $removeFiles;

    /**
     * Remove constructor.
     *
     * @param RemoveFiles $removeFiles
     */
    public function __construct(RemoveFiles $removeFiles)
    {
        $this->removeFiles = $removeFiles;
    }

    /**
     * Remove files by list of ids.
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

        $files = $this->removeFiles->execute(...$ids);

        $event->getResponse()->setBody($files);
    }
}
