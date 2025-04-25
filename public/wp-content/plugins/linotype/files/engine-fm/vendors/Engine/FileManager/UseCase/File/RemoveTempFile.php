<?php

namespace Engine\FileManager\UseCase\File;

use Engine\FileManager\Entity\File;
use Engine\FileManager\Setting\Contract\AppConfig;
use Engine\FileManager\Value\Path;

/**
 * Class RemoveTempFile.
 *
 * Service to remove files from temporary folder.
 */
class RemoveTempFile
{
    /**
     * @var Path
     */
    private $temporaryPath;

    /**
     * RemoveTempFile constructor.
     *
     * @param AppConfig $config
     */
    public function __construct(AppConfig $config)
    {
        $this->temporaryPath = $config->getTemporaryPath();
    }

    /**
     * Removes a file from temporary path.
     *
     * @param File $file
     */
    public function execute(File $file)
    {
        if ($this->isTempFile($file)) {
            unlink($file->getPath());
        }
    }

    /**
     * Checks if a file in the temporary folder.
     *
     * @param File $file
     *
     * @return bool
     */
    private function isTempFile(File $file): bool
    {
        return $file->getPath()->within($this->temporaryPath) && file_exists($file->getPath());
    }
}
