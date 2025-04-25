<?php

namespace Engine\FileManager\Action;

use Engine\FileManager\UseCase\File\MoveFiles;
use Engine\FileManager\Value\FileId;
use Engine\Http\Event\HttpExchangeEvent;
use Engine\Rx\Abstraction\Observer;
use Engine\Rx\Service\ObserverStub;

/**
 * Class Move.
 *
 * Moves files by list ids to directory with parentId.
 */
class Move implements Observer
{
    use ObserverStub;

    /**
     * @var MoveFiles
     */
    private $moveFiles;

    /**
     * Move constructor.
     *
     * @param MoveFiles $moveFiles
     */
    public function __construct(MoveFiles $moveFiles)
    {
        $this->moveFiles = $moveFiles;
    }

    /**
     * Moves files by list ids to directory with parentId.
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

        $files = $this->moveFiles->execute($parentId, ...$ids);

        $event->getResponse()->setBody($files);
    }
}
