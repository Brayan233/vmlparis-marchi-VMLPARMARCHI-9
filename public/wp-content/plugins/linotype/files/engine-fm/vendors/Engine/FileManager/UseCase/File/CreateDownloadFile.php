<?php

namespace Engine\FileManager\UseCase\File;

use Engine\FileManager\Entity\Directory;
use Engine\FileManager\Entity\File;
use Engine\FileManager\Entity\Hyperlink;
use Engine\FileManager\Persistence\Contract\FileRepository;
use Engine\FileManager\Setting\Contract\AppConfig;
use Engine\FileManager\Value\FileId;
use Engine\FileManager\Value\Path;

/**
 * Class CreateDownloadFile.
 *
 * Service to prepare a download file.
 */
class CreateDownloadFile
{
    /**
     * @var FileRepository
     */
    private $repository;

    /**
     * @var ArchiveFiles
     */
    private $archiveFiles;

    /**
     * @var Path
     */
    private $temporaryPath;

    /**
     * CreateDownloadFile constructor.
     *
     * @param FileRepository $repository
     * @param ArchiveFiles   $archiveFiles
     * @param AppConfig         $config
     */
    public function __construct(FileRepository $repository, ArchiveFiles $archiveFiles, AppConfig $config)
    {
        $this->repository = $repository;
        $this->archiveFiles = $archiveFiles;
        $this->temporaryPath = $config->getTemporaryPath();
    }

    /**
     * Returns a prepared to download file by file id.
     * If passed one file then it returns it.
     * If passed directory then it returns an archive.
     * If passed several files then it returns an archive.
     *
     * @param FileId[] ...$ids
     *
     * @return Directory|File|Hyperlink
     */
    public function execute(FileId ...$ids)
    {
        if (1 === count($ids)) {
            $file = $this->repository->readFile($ids[0], true);
            if ($file instanceof Directory) {
                $archivePath = $this->temporaryPath->join($file->getPath()->getFileName()->getBaseName().'.zip');
                $file = $this->archiveFiles->execute($archivePath, ...(array) $file->getChildren());
            }
        } else {
            $files = [];
            foreach ($ids as $id) {
                $files[] = $this->repository->readFile($id, true);
            }
            $archivePath = $this->temporaryPath->join(date('Y-m-d H_i_s').'.zip');

            return $this->archiveFiles->execute($archivePath, ...$files);
        }

        return $file;
    }
}
