<?php

namespace Engine\FileManager\UseCase\File;

use DateTime;
use Engine\FileManager\Entity\Directory;
use Engine\FileManager\Entity\File;
use Engine\FileManager\Persistence\Contract\FileRepository;
use Engine\FileManager\Value\FileId;
use Engine\FileManager\Value\Permissions;

/**
 * Class SetFilePermissions.
 *
 * Service to set file permissions.
 */
class SetFilePermissions
{
    /**
     * @var FileRepository
     */
    private $repository;

    /**
     * SetFilePermissions constructor.
     *
     * @param FileRepository $repository
     */
    public function __construct(FileRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Sets file permissions.
     *
     * @param FileId      $id
     * @param Permissions $permissions
     *
     * @return File|Directory
     */
    public function execute(FileId $id, Permissions $permissions): File
    {
        $file = $this->repository->readFile($id);

        $file
            ->setPermissions($permissions)
            ->setLastModified(new DateTime());

        $this->repository->saveFiles($file);

        return $file;
    }
}
