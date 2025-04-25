<?php

namespace Engine\FileManager\UseCase\File;

use DomainException;
use Engine\FileManager\Entity\Directory;
use Engine\FileManager\Persistence\Contract\FileRepository;
use Engine\FileManager\Value\FileId;
use Engine\FileManager\Value\Pattern;

/**
 * Class SearchFiles.
 *
 * Service to search files.
 */
class SearchFiles
{
    /**
     * @var FileRepository
     */
    private $repository;

    /**
     * SearchFiles constructor.
     *
     * @param FileRepository $repository
     */
    public function __construct(FileRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Searches files matches to a pattern in directory with id.
     *
     * @param FileId  $id
     * @param Pattern $pattern
     *
     * @return Directory
     */
    public function execute(FileId $id, Pattern $pattern): Directory
    {
        $directory = $this->repository->readFile($id);

        if (!$directory instanceof Directory) {
            throw new DomainException('Invalid id was provided. Must be directory.');
        }

        $files = $this->repository->searchFiles($id, $pattern);
        $directory
            ->setChildren(...$files)
            ->setPattern($pattern);

        return $directory;
    }
}
