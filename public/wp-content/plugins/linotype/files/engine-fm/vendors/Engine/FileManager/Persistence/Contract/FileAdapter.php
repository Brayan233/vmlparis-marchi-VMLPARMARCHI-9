<?php

namespace Engine\FileManager\Persistence\Contract;

use stdClass;

/**
 * Interface FileAdapter.
 */
interface FileAdapter
{
    /**
     * Reads file ar directory by id.
     * The flag recursively works only when directory reads.
     *
     * @param string    $id
     * @param bool|null $recursively
     *
     * @return stdClass
     */
    public function readFile(string $id, bool $recursively = null): stdClass;

    /**
     * Searches for a pattern in the directory.
     *
     * @param string $id
     * @param string $pattern
     *
     * @return array
     */
    public function searchFiles(string $id, string $pattern): array;

    /**
     * Saves file to storage.
     *
     * @param stdClass $raw
     */
    public function saveFile(stdClass $raw);

    /**
     * Copies file to new path.
     *
     * @param stdClass $raw
     */
    public function copyFile(stdClass $raw);

    /**
     * Moves file to new path.
     *
     * @param stdClass $raw
     */
    public function moveFile(stdClass $raw);

    /**
     * Remove files by id.
     *
     * @param string $id
     */
    public function removeFile(string $id);

    /**
     * Checks if file exists by id.
     *
     * @param string $id
     *
     * @return bool
     */
    public function exists(string $id): bool;

    /**
     * Returns file size by id.
     *
     * @param string $id
     *
     * @return int
     */
    public function getFileSize(string $id): int;
}
