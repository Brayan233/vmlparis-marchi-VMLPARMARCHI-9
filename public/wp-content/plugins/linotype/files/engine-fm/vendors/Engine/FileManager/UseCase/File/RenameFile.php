<?php

namespace Engine\FileManager\UseCase\File;

use DateTime;
use DomainException;
use Engine\FileManager\Entity\Directory;
use Engine\FileManager\Entity\File;
use Engine\FileManager\Persistence\Contract\FileRepository;
use Engine\FileManager\Value\FileId;
use Engine\FileManager\Value\FileName;

/**
 * Class RenameFile.
 *
 * Service to rename file.
 */
class RenameFile
{
    /**
     * @var FileRepository
     */
    private $repository;

    /**
     * RenameFile constructor.
     *
     * @param FileRepository $repository
     */
    public function __construct(FileRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Renames file.
     *
     * @param FileId   $id
     * @param FileName $name
     *
     * @return File|Directory
     */
    public function execute(FileId $id, FileName $name): File
    {
        if (!$id->hasParent()) {
            throw new DomainException(sprintf('Unable to rename file \'%s\'', $id));
        }

        $file = $this->repository->readFile($id);

        $file
            ->setPath($file->getPath()->getParent()->join($name))
            ->setLastModified(new DateTime());

        $this->repository->saveFiles($file);

        return $file;
    }
}
