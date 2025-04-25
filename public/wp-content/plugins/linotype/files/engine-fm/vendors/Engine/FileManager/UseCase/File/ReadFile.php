<?php

namespace Engine\FileManager\UseCase\File;

use Engine\FileManager\Entity\Directory;
use Engine\FileManager\Persistence\Contract\FileRepository;
use Engine\FileManager\Value\FileId;
use Engine\FileManager\Entity\File;

/**
 * Class ReadFile.
 *
 * Service to read file, directory, hyperlink.
 */
class ReadFile
{
    /**
     * @var FileRepository
     */
    private $repository;

    /**
     * ReadFile constructor.
     *
     * @param FileRepository $repository
     */
    public function __construct(FileRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Reads file, directory, hyperlink by id.
     * In case a directory there is an opportunity to get all children
     * as a tree if specify the param recursively as true,
     * by default it returns a directory with the first level of children.
     *
     * @param FileId $id
     * @param bool   $recursively
     *
     * @return Directory|File
     */
    public function execute(FileId $id, bool $recursively = null): File
    {
        return $this->repository->readFile($id, $recursively);
    }
}
