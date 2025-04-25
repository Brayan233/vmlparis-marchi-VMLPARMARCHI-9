<?php

namespace Engine\FileManager\View;

use DateTime;
use Engine\FileManager\Entity\Directory;
use Engine\FileManager\Entity\File;
use Engine\FileManager\Entity\Hyperlink;
use Engine\FileManager\Value\FileId;
use Engine\FileManager\Value\Path;
use Engine\FileManager\Value\Permissions;
use Engine\FileManager\Value\Size;
use JsonSerializable;

/**
 * Class FileView.
 *
 * File entity representation for server application response.
 */
class FileView implements JsonSerializable
{
    /**
     * @var File|Directory|Hyperlink
     */
    protected $file;

    /**
     * FileView constructor.
     *
     * @param File|Directory|Hyperlink $directory
     */
    public function __construct(File $directory)
    {
        $this->file = $directory;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'id' => (string) $this->getId(),
            'parentId' => $this->getId()->hasParent() ? (string) $this->getId()->getParent() : null,
            'name' => (string) $this->getPath()->getFileName(),
            'baseName' => $this->getPath()->getFileName()->getBaseName(),
            'extension' => $this->getPath()->getFileName()->getExtension(),
            'type' => $this->getType(),
            'size' => $this->getSize()->getValue(),
            'permissions' => (string) $this->getPermissions(),
            'lastModified' => $this->getLastModified()->getTimestamp(),
            'breadcrumbs' => $this->createBreadcrumbs($this->getId()),
            'class' => $this->getClass(),
        ];
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return 'file';
    }

    /**
     * @return FileId
     */
    public function getId(): FileId
    {
        return $this->file->getId();
    }

    /**
     * @return Path
     */
    public function getPath(): Path
    {
        return $this->file->getPath();
    }

    /**
     * @return Size
     */
    public function getSize(): Size
    {
        return $this->file->getSize();
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->file->getType();
    }

    /**
     * @return Permissions
     */
    public function getPermissions(): Permissions
    {
        return $this->file->getPermissions();
    }

    /**
     * @return DateTime
     */
    public function getLastModified(): DateTime
    {
        return $this->file->getLastModified();
    }

    /**
     * @param FileId $id
     *
     * @return array
     */
    private function createBreadcrumbs(FileId $id)
    {
        $breadcrumbs = [];
        while ($id->hasParent()) {
            $breadcrumbs[] = [
                'id' => (string) $id,
                'name' => mb_substr($id, mb_strrpos($id, '/') + 1),
            ];

            $id = $id->getParent();
        }

        $breadcrumbs[] = [
            'id' => '/',
            'name' => '',
        ];

        return array_reverse($breadcrumbs);
    }
}
