<?php

namespace Engine\FileManager\Action;

use Engine\FileManager\Entity\File;
use Engine\FileManager\UseCase\File\CreateDownloadFile;
use Engine\FileManager\UseCase\File\RemoveTempFile;
use Engine\FileManager\Value\FileId;
use Engine\Http\Event\HttpExchangeEvent;
use Engine\Rx\Abstraction\Observer;
use Throwable;

/**
 * Class Download.
 *
 * Gives files for download by list of ids.
 */
class Download implements Observer
{
    /**
     * @var CreateDownloadFile
     */
    private $createDownloadFile;

    /**
     * @var RemoveTempFile
     */
    private $removeTempFile;

    /**
     * @var File|null
     */
    private $file;

    /**
     * Download constructor.
     *
     * @param CreateDownloadFile $createDownloadFile
     * @param RemoveTempFile     $removeTempFile
     */
    public function __construct(CreateDownloadFile $createDownloadFile, RemoveTempFile $removeTempFile)
    {
        $this->createDownloadFile = $createDownloadFile;
        $this->removeTempFile = $removeTempFile;
    }

    /**
     * Gives files for download by list of ids.
     *
     * @param HttpExchangeEvent $event
     */
    public function onNext($event)
    {
        $ids = $event->getRequest()->getQueryParam('id');

        if (!is_array($ids)) {
            $ids = [$ids];
        }

        foreach ($ids as &$id) {
            $id = new FileId($id);
        }

        $this->file = $this->createDownloadFile->execute(...$ids);

        $event->getResponse()
            ->setHeader('Content-Description', 'File Transfer')
            ->setHeader('Content-Type', 'application/octet-stream')
            ->setHeader(
                'Content-Disposition',
                'attachment; filename="'.$this->file->getPath()->getFileName()->getValue().'"'
            )
            ->setHeader('Expires', '0')
            ->setHeader('Cache-Control', 'must-revalidate')
            ->setHeader('Pragma', 'public')
            ->setHeader('Content-Length', $this->file->getSize()->getValue());
    }

    /**
     * Writes a file to output and removes an archive from the temporary folder.
     */
    public function onComplete()
    {
        if (ob_get_level()) {
            ob_end_clean();
        }

        readfile($this->file->getPath());

        $this->removeTempFile->execute($this->file);
    }

    /**
     * Removes an archive from the temporary folder.
     *
     * @param Throwable $throwable
     * @param object    $event
     */
    public function onError(Throwable $throwable, $event)
    {
        if (null !== $this->file) {
            $this->removeTempFile->execute($this->file);
        }
    }
}
