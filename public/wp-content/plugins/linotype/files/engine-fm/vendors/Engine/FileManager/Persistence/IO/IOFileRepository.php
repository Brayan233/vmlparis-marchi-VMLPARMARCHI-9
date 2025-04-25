<?php

namespace Engine\FileManager\Persistence\IO;

use Engine\FileManager\Entity\Directory;
use Engine\FileManager\Entity\File;
use Engine\FileManager\Entity\FileCollection;
use Engine\FileManager\Persistence\Contract\FileAdapter;
use Engine\FileManager\Persistence\Contract\FileFactory;
use Engine\FileManager\Persistence\Contract\FileRepository;
use Engine\FileManager\Persistence\IO\Exception\IONotFoundException;
use Engine\FileManager\Persistence\Exception\NotFoundException;
use Engine\FileManager\Value\FileId;
use Engine\FileManager\Value\Pattern;
use Engine\FileManager\Value\Size;

/**
 * Class IOFileRepository.
 *
 * IO system file repository implementation.
 */
class IOFileRepository implements FileRepository
{
    /**
     * @var FileAdapter
     */
    private $fileAdapter;

    /**
     * @var FileFactory
     */
    private $factory;

    /**
     * IOFileRepository constructor.
     *
     * @param FileAdapter $fileAdapter
     * @param FileFactory $factory
     */
    public function __construct(FileAdapter $fileAdapter, FileFactory $factory)
    {
        $this->fileAdapter = $fileAdapter;
        $this->factory = $factory;

        $this->initialize();
    }

    /**
     * {@inheritdoc}
     */
    public function readFile(FileId $id, bool $recursively = null): File
    {
        try {
            $file = $this->factory->createFile($this->fileAdapter->readFile($id, $recursively));
        } catch (IONotFoundException $exception) {
            throw new NotFoundException(sprintf('File or directory \'%s\' is not exists or it\'s not readable', $id));
        }

        return $file;
    }

    /**
     * {@inheritdoc}
     */
    public function searchFiles(FileId $id, Pattern $pattern): FileCollection
    {
        $rawList = $this->fileAdapter->searchFiles($id, $pattern);

        $files = new FileCollection();
        foreach ($rawList as $raw) {
            $files->addFile($this->factory->createFile($raw));
        }

        return $files;
    }

    /**
     * {@inheritdoc}
     */
    public function saveFiles(File ...$files)
    {
        foreach ($files as $file) {
            $raw = $this->factory->recycleFile($file);
            $this->fileAdapter->saveFile($raw);
            $this->alignById($file, $raw);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function copyFiles(File ...$files)
    {
        foreach ($files as $file) {
            $raw = $this->factory->recycleFile($file);
            $this->fileAdapter->copyFile($raw);
            $this->alignById($file, $raw);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function moveFiles(File ...$files)
    {
        foreach ($files as $file) {
            $raw = $this->factory->recycleFile($file);
            $this->fileAdapter->moveFile($raw);
            $this->alignById($file, $raw);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeFiles(File ...$files)
    {
        foreach ($files as $file) {
            $this->fileAdapter->removeFile($file->getId());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function exists(FileId $id): bool
    {
        return $this->fileAdapter->exists($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getFileSize(FileId $id): Size
    {
        return new Size($this->fileAdapter->getFileSize($id));
    }

    /**
     * Sets new file id if there is not equal with raw.
     *
     * @param File $file
     * @param $raw
     */
    private function alignById(File $file, $raw)
    {
        if (!$file->getId()->isEqual($raw->id)) {
            $file->setId(new FileId($raw->id));
        }

        if ($file instanceof Directory && $file->hasChildren()) {
            foreach ($file->getChildren() as $index => $child) {
                $this->alignById($child, $raw->children[$index]);
            }
        }
    }

    /**
     * Initializes repository.
     * Creates folder for trash.
     */
    private function initialize()
    {
        $trashId = new FileId('/$trash');
        if (!$this->exists($trashId)) {
            $this->saveFiles((new Directory())->setId($trashId));
        }
    }
}
