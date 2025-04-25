<?php

namespace Engine\FileManager\UseCase\File;

use DateTime;
use Engine\FileManager\Entity\Directory;
use Engine\FileManager\Entity\File;
use Engine\FileManager\Persistence\Contract\FileRepository;
use Engine\FileManager\Value\FileId;
use Engine\FileManager\Value\FileName;
use Engine\FileManager\Value\Permissions;
use Engine\FileManager\Value\Size;

/**
 * Class CreateDirectory.
 *
 * Service to create directory.
 */
class CreateDirectory
{
    /**
     * @var FileRepository
     */
    private $repository;

    /**
     * CreateDirectory constructor.
     *
     * @param FileRepository $repository
     */
    public function __construct(FileRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Creates an empty directory in existing one.
     *
     * @param FileName $name
     * @param FileId   $parentId
     *
     * @return File
     */
    public function execute(FileName $name, FileId $parentId): File
    {
        $parent = $this->repository->readFile($parentId);

        $file = (new Directory())
            ->setId($parentId->join($name))
            ->setPath($parent->getPath()->join($name, true))
            ->setSize(new Size(0))
            ->setType('directory')
            ->setPermissions(new Permissions(0755))
            ->setLastModified(new DateTime());

        $this->repository->saveFiles($file);

        return $file;
    }
}
