<?php

namespace Engine\FileManager\Persistence\IO;

use DateTime;
use Engine\FileManager\Entity\Directory;
use Engine\FileManager\Entity\File;
use Engine\FileManager\Entity\Hyperlink;
use Engine\FileManager\Persistence\Contract\FileFactory;
use Engine\FileManager\Value\FileId;
use Engine\FileManager\Value\Path;
use Engine\FileManager\Value\Permissions;
use Engine\FileManager\Value\Size;
use Engine\Http\Value\Url;
use stdClass;

/**
 * Class IOFileFactory.
 *
 * IO system file factory implementation.
 */
class IOFileFactory implements FileFactory
{
    /**
     * {@inheritdoc}
     */
    public function createFile(stdClass $raw): File
    {
        if ($raw->isDirectory) {
            $file = new Directory();
        } elseif ($raw->isHyperlink) {
            $file = new Hyperlink();
            $file->setUrl(new Url($raw->url));
        } else {
            $file = new File();
        }

        if (null !== $raw->id) {
            $file->setId(new FileId($raw->id));
        }

        $file
            ->setPath(new Path($raw->path, $raw->isDirectory))
            ->setSize(new Size((int) $raw->size))
            ->setType($raw->type)
            ->setPermissions(new Permissions($raw->permissions))
            ->setLastModified(new DateTime(date('Y-m-d H:i:s', $raw->lastModified)));

        if (isset($raw->children)) {
            $file->setChildren(...[]);
            foreach ($raw->children as $child) {
                $file->addChild($this->createFile($child));
            }
        }

        return $file;
    }

    /**
     * {@inheritdoc}
     */
    public function recycleFile(File $file): stdClass
    {
        $raw = new stdClass();
        $raw->id = null !== $file->getId() ? $file->getId()->getValue() : null;
        $raw->path = null !== $file->getPath() ? $file->getPath()->getValue() : null;
        $raw->size = $file->getSize() ? $file->getSize()->getValue() : null;
        $raw->type = $file->getType();
        $raw->permissions = null !== $file->getPermissions() ? $file->getPermissions()->getValue() : null;
        $raw->lastModified = null !== $file->getLastModified() ? $file->getLastModified()->getTimestamp() : null;
        $raw->isHyperlink = $file instanceof Hyperlink;
        $raw->isDirectory = $file instanceof Directory;

        if ($file instanceof Hyperlink) {
            $raw->url = $file->getUrl()->getValue();
        }

        if ($file instanceof Directory) {
            $raw->hasChildren = $file->hasChildren();
            $raw->children = null;
            if ($file->hasChildren()) {
                $raw->children = [];
                foreach ($file->getChildren() as $child) {
                    $raw->children[] = $this->recycleFile($child);
                }
            }
        }

        return $raw;
    }
}
