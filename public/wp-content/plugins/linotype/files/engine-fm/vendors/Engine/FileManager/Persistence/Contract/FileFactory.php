<?php

namespace Engine\FileManager\Persistence\Contract;

use Engine\FileManager\Entity\File;
use stdClass;

/**
 * Interface FileFactory.
 */
interface FileFactory
{
    /**
     * Creates file entity from simple object.
     *
     * @param stdClass $raw
     *
     * @return File
     */
    public function createFile(stdClass $raw): File;

    /**
     * Creates simple object from file entity.
     *
     * @param File $file
     *
     * @return stdClass
     */
    public function recycleFile(File $file): stdClass;
}
