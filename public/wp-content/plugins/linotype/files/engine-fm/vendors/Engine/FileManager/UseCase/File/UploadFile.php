<?php

namespace Engine\FileManager\UseCase\File;

use DateTime;
use DomainException;
use Engine\FileManager\Entity\Directory;
use Engine\FileManager\Persistence\Contract\FileRepository;
use Engine\FileManager\Setting\Contract\AppConfig;
use Engine\FileManager\Value\FileId;
use Engine\FileManager\Entity\File;
use Engine\FileManager\Value\Permissions;
use Engine\FileManager\Value\Size;
use Engine\Http\Value\UploadedFile;

/**
 * Class UploadFile.
 *
 * Service to upload file.
 */
class UploadFile
{
    /**
     * @var FileRepository
     */
    private $repository;

    /**
     * @var AppConfig
     */
    private $config;

    /**
     * UploadFile constructor.
     *
     * @param FileRepository $repository
     * @param AppConfig         $config
     */
    public function __construct(FileRepository $repository, AppConfig $config)
    {
        $this->repository = $repository;
        $this->config = $config;
    }

    /**
     * Uploads file and puts it to directory with parentId.
     *
     * @param UploadedFile $uploadedFile
     * @param FileId       $parentId
     *
     * @return File
     */
    public function execute(UploadedFile $uploadedFile, FileId $parentId): File
    {
        $this->validateUploadedFile($uploadedFile);

        $parent = $this->repository->readFile($parentId);

        if (!$parent instanceof Directory) {
            throw new DomainException('Invalid parent id was provided. Must be directory.');
        }

        $file = (new File())
            ->setId($parentId->join($uploadedFile->getName()))
            ->setPath($parent->getPath()->join($uploadedFile->getName()))
            ->setSize(new Size($uploadedFile->getSize()))
            ->setType($uploadedFile->getType())
            ->setPermissions(new Permissions(0755))
            ->setLastModified(new DateTime());

        $uploadedFile->moveTo($file->getPath());

        $this->repository->saveFiles($file);

        return $file;
    }

    /**
     * Check if file is valid.
     *
     * @param UploadedFile $uploadedFile
     */
    private function validateUploadedFile(UploadedFile $uploadedFile)
    {
        if (!$this->config->isMimeTypeAllowed($uploadedFile->getType())) {
            throw new DomainException(sprintf(
                'The file \'%s\' has unsupported type \'%s\'',
                $uploadedFile->getName(),
                $uploadedFile->getType()
            ));
        }

        if (!$this->config->isFileSizeAllowed($uploadedFile->getSize())) {
            throw new DomainException(sprintf(
                'The file \'%s\' exceeds the allowed size %s',
                $uploadedFile->getName(),
                $this->config->getUploadMaxFileSize()->toHumanString()
            ));
        }

        $storageSize = $this->repository->getFileSize(new FileId('/'));
        if ($storageSize->getValue() + $uploadedFile->getSize() > $this->config->getStorageMaxSize()->getValue()) {
            throw new DomainException(sprintf(
                'The file \'%s\' overflows the storage. Max size is %s',
                $uploadedFile->getName(),
                $this->config->getStorageMaxSize()->toHumanString()
            ));
        }
    }
}
