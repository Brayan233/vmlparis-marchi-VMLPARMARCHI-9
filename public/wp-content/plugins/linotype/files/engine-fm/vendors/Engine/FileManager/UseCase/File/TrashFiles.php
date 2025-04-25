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
 * Class TrashFiles.
 *
 * Service to put the files to the trash.
 */
class TrashFiles
{
    /**
     * @var FileRepository
     */
    private $repository;

    /**
     * TrashFiles constructor.
     *
     * @param FileRepository $repository
     */
    public function __construct(FileRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Puts the files to using list of ids to the trash directory.
     *
     * @param FileId[] ...$ids
     *
     * @return FileCollection
     */
    public function execute(FileId ...$ids): FileCollection
    {
        $parent = $this->repository->readFile(new FileId('/$trash'));

        if (!$parent instanceof Directory) {
            throw new DomainException('Invalid parent id was provided. Must be directory.');
        }

        $originalFiles = new FileCollection();
        $files = new FileCollection();
        foreach ($ids as $id) {
            $file = $this->repository->readFile($id, true);
            $originalFiles->addFile(clone $file);
            $this->trashFile($file, $parent);
            $files->addFile($file);
        }

        $this->repository->moveFiles(...$files);

        return $originalFiles;
    }

    /**
     * Puts the file to the trash directory.
     *
     * @param File      $file
     * @param Directory $parent
     */
    private function trashFile(File $file, Directory $parent)
    {
        $file
            ->setPath($parent->getPath()->join($file->getPath()->getFileName()))
            ->setLastModified(new DateTime());

        if ($file instanceof Directory && $file->hasChildren()) {
            foreach ($file->getChildren() as $child) {
                $this->trashFile($child, $file);
            }
        }
    }
}
