<?php

namespace Engine\FileManager\Action;

use Engine\FileManager\UseCase\File\SearchFiles;
use Engine\FileManager\Value\FileId;
use Engine\FileManager\Value\Pattern;
use Engine\Http\Event\HttpExchangeEvent;
use Engine\Rx\Abstraction\Observer;
use Engine\Rx\Service\ObserverStub;

/**
 * Class Search.
 *
 * Searches for a pattern in the directory.
 */
class Search implements Observer
{
    use ObserverStub;

    /**
     * @var SearchFiles
     */
    private $searchFiles;

    /**
     * Search constructor.
     *
     * @param SearchFiles $searchFiles
     */
    public function __construct(SearchFiles $searchFiles)
    {
        $this->searchFiles = $searchFiles;
    }

    /**
     * Searches for a pattern in the directory.
     *
     * @param HttpExchangeEvent $event
     */
    public function onNext($event)
    {
        $id = new FileId($event->getRequest()->getBodyParam('id'));
        $pattern = new Pattern($event->getRequest()->getBodyParam('pattern'));

        $file = $this->searchFiles->execute($id, $pattern);

        $event->getResponse()->setBody($file);
    }
}
