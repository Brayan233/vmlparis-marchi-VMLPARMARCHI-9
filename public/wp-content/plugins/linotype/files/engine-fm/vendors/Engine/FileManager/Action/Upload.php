<?php

namespace Engine\FileManager\Action;

use Engine\FileManager\Value\FileId;
use Engine\FileManager\UseCase\File\UploadFile;
use Engine\Http\Event\HttpExchangeEvent;
use Engine\Rx\Abstraction\Observer;
use Engine\Rx\Service\ObserverStub;

/**
 * Class Upload.
 *
 * Uploads files to directory with parentId.
 */
class Upload implements Observer
{
    use ObserverStub;

    /**
     * @var UploadFile
     */
    private $uploadFile;

    /**
     * Upload constructor.
     *
     * @param UploadFile $uploadFile
     */
    public function __construct(UploadFile $uploadFile)
    {
        $this->uploadFile = $uploadFile;
    }

    /**
     * Uploads files to directory with parentId.
     *
     * @param HttpExchangeEvent $event
     */
    public function onNext($event)
    {
        $uploadedFile = $event->getRequest()->getUploadedFiles()['file'];
        $parentId = new FileId($event->getRequest()->getBodyParam('parentId'));

        $file = $this->uploadFile->execute($uploadedFile, $parentId);

        $event->getResponse()->setBody($file);
    }
}
