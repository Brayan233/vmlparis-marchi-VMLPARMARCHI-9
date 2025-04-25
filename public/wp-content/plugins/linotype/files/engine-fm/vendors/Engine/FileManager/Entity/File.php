<?php

namespace Engine\FileManager\Entity;

use DateTime;
use Engine\FileManager\Value\FileId;
use Engine\FileManager\Value\Path;
use Engine\FileManager\Value\Permissions;
use Engine\FileManager\Value\Size;

/**
 * Class File.
 *
 * File entity implementation.
 */
class File
{
    /**
     * @var FileId|null
     */
    private $id;

    /**
     * @var Path|null
     */
    private $path;

    /**
     * @var Size|null
     */
    private $size;

    /**
     * @var string|null
     */
    private $type;

    /**
     * @var Permissions|null
     */
    private $permissions;

    /**
     * @var DateTime|null
     */
    private $lastModified;

    /**
     * @return FileId|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param FileId $id
     *
     * @return self
     */
    public function setId(FileId $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Path|null
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param Path $path
     *
     * @return self
     */
    public function setPath(Path $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return Size|null
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param Size $size
     *
     * @return self
     */
    public function setSize(Size $size): self
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Permissions|null
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * @param Permissions $permissions
     *
     * @return self
     */
    public function setPermissions(Permissions $permissions): self
    {
        $this->permissions = $permissions;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }

    /**
     * @param DateTime $lastModified
     *
     * @return self
     */
    public function setLastModified(DateTime $lastModified): self
    {
        $this->lastModified = $lastModified;

        return $this;
    }
}
