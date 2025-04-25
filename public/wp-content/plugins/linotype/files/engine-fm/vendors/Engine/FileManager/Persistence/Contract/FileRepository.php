<?php

namespace Engine\FileManager\Persistence\Contract;

use Engine\FileManager\Entity\Directory;
use Engine\FileManager\Entity\File;
use Engine\FileManager\Entity\FileCollection;
use Engine\FileManager\Entity\Hyperlink;
use Engine\FileManager\Value\FileId;
use Engine\FileManager\Value\Pattern;
use Engine\FileManager\Value\Size;

/**
 * Interface FileRepository.
 */
interface FileRepository
{
    /**
     * Returns file, directory or hyperlink by id.
     * In case a directory there is an opportunity to get all children
     * as a tree if specify the param recursively as true,
     * by default it returns a directory with the first level of children.
     *
     * @param FileId    $id
     * @param bool|null $recursively
     *
     * @return File|Directory|Hyperlink
     */
    public function readFile(FileId $id, bool $recursively = null): File;

    /**
     * Searches files which matches to pattern.
     *
     * @param FileId  $id
     * @param Pattern $pattern
     *
     * @return FileCollection
     */
    public function searchFiles(FileId $id, Pattern $pattern): FileCollection;

    /**
     * Saves files to storage.
     *
     * @param File[] ...$files
     *
     * @return mixed
     */
    public function saveFiles(File ...$files);

    /**
     * Copies files to path that specified in file param called path.
     *
     * @param File[] ...$files
     *
     * @return mixed
     */
    public function copyFiles(File ...$files);

    /**
     * Moves files to path that specified in file param called path.
     *
     * @param File[] ...$files
     *
     * @return mixed
     */
    public function moveFiles(File ...$files);

    /**
     * Removes files permanently.
     *
     * @param File[] ...$files
     *
     * @return mixed
     */
    public function removeFiles(File ...$files);

    /**
     * Checks if file exists by file id.
     *
     * @param FileId $id
     *
     * @return bool
     */
    public function exists(FileId $id): bool;

    /**
     * Returns file size as number of bytes.
     *
     * @param FileId $id
     *
     * @return Size
     */
    public function getFileSize(FileId $id): Size;
}
