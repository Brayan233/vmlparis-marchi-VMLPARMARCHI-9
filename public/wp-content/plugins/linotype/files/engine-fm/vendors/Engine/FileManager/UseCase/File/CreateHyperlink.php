<?php

namespace Engine\FileManager\UseCase\File;

use DateTime;
use Engine\FileManager\Entity\Hyperlink;
use Engine\FileManager\Persistence\Contract\FileRepository;
use Engine\FileManager\Value\FileId;
use Engine\FileManager\Value\FileName;
use Engine\FileManager\Value\Permissions;
use Engine\FileManager\Value\Size;
use Engine\Http\Value\Url;

/**
 * Class CreateHyperlink.
 *
 * Service to create hyperlink.
 */
class CreateHyperlink
{
    /**
     * @var FileRepository
     */
    private $repository;

    /**
     * CreateHyperlink constructor.
     *
     * @param FileRepository $repository
     */
    public function __construct(FileRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Creates a hyperlink in existing directory.
     *
     * @param FileName $name
     * @param FileId   $parentId
     * @param Url      $url
     *
     * @return Hyperlink
     */
    public function execute(FileName $name, FileId $parentId, Url $url): Hyperlink
    {
        $parent = $this->repository->readFile($parentId);
        $name = $this->createFileName($name, $url);

        /**
         * @var Hyperlink
         */
        $file = (new Hyperlink())
            ->setUrl($url)
            ->setId($parentId->join($name))
            ->setPath($parent->getPath()->join($name))
            ->setSize(new Size(mb_strlen((string) $url)))
            ->setType('hyperlink')
            ->setPermissions(new Permissions(0755))
            ->setLastModified(new DateTime());

        $this->repository->saveFiles($file);

        return $file;
    }

    /**
     * Adds scheme as an extension for file name.
     *
     * @param FileName $name
     * @param Url      $url
     *
     * @return FileName
     */
    private function createFileName(FileName $name, Url $url)
    {
        return new FileName($name.'.'.($url->getScheme() ?? 'http'));
    }
}
