<?php

namespace Engine\FileManager\UseCase\File;

use DomainException;
use Engine\FileManager\Entity\FileCollection;
use Engine\FileManager\Persistence\Contract\FileRepository;
use Engine\FileManager\Value\FileId;

/**
 * Class RemoveFiles.
 *
 * Service to remove files to a directory.
 */
class RemoveFiles
{
    /**
     * @var FileRepository
     */
    private $repository;

    /**
     * RemoveFile constructor.
     *
     * @param FileRepository $repository
     */
    public function __construct(FileRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Removes files using list of ids to a directory with parentId.
     *
     * @param FileId[] ...$ids
     *
     * @return FileCollection
     */
    public function execute(FileId ...$ids): FileCollection
    {
        $files = new FileCollection();

        foreach ($ids as $id) {
            if ($id->isRoot()) {
                throw new DomainException('An attempt to remove storage root');
            }
            $files->addFile($this->repository->readFile($id));
        }

        $this->repository->removeFiles(...$files);

        return $files;
    }
}
