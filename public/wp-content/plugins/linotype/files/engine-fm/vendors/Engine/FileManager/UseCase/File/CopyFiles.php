<?php

namespace Engine\FileManager\UseCase\File;

use DateTime;
use DomainException;
use Engine\FileManager\Entity\Directory;
use Engine\FileManager\Entity\File;
use Engine\FileManager\Entity\FileCollection;
use Engine\FileManager\Persistence\Contract\FileRepository;
use Engine\FileManager\Value\FileId;

/**
 * Class CopyFiles.
 *
 * Service to copy files to a directory.
 */
class CopyFiles
{
    /**
     * @var FileRepository
     */
    private $repository;

    /**
     * CopyFile constructor.
     *
     * @param FileRepository $repository
     */
    public function __construct(FileRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Copies files using list of ids to a directory with parentId.
     *
     * @param FileId   $parentId
     * @param FileId[] ...$ids
     *
     * @return FileCollection
     */
    public function execute(FileId $parentId, FileId ...$ids): FileCollection
    {
        $parent = $this->repository->readFile($parentId);

        if (!$parent instanceof Directory) {
            throw new DomainException('Invalid parent id was provided. Must be directory.');
        }

        $files = new FileCollection();
        foreach ($ids as $id) {
            $file = $this->repository->readFile($id, true);
            $this->copyFile($file, $parent);
            $files->addFile($file);
        }

        $this->repository->copyFiles(...$files);

        return $files;
    }

    /**
     * Copies file to directory.
     *
     * @param File      $file
     * @param Directory $parent
     */
    private function copyFile(File $file, Directory $parent)
    {
        $file
            ->setPath($parent->getPath()->join($file->getPath()->getFileName()))
            ->setLastModified(new DateTime());

        if ($file instanceof Directory && $file->hasChildren()) {
            foreach ($file->getChildren() as $child) {
                $this->copyFile($child, $file);
            }
        }
    }
}
