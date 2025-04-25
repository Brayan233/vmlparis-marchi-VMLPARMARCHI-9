<?php

namespace Engine\FileManager\UseCase\File;

use DomainException;
use Engine\FileManager\Entity\Directory;
use Engine\FileManager\Entity\File;
use Engine\FileManager\Value\Path;
use Engine\FileManager\Value\Size;
use ZipArchive;

/**
 * Class ArchiveFiles.
 *
 * Service to create an archive from file list.
 */
class ArchiveFiles
{
    /**
     * Creates archive from file list.
     *
     * @param Path   $archivePath
     * @param File[] ...$files
     *
     * @return File
     *
     * @throws DomainException if it was an attempt to create an empty archive
     */
    public function execute(Path $archivePath, File ...$files)
    {
        if (empty($files)) {
            throw new DomainException('An attempt to create an empty archive');
        }

        $archive = new ZipArchive();
        $archive->open($archivePath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $this->populateArchive($archive, null, ...$files);
        $archive->close();

        return (new File())
            ->setPath($archivePath)
            ->setSize(new Size(filesize($archivePath)));
    }

    /**
     * Populates an archive with files.
     *
     * @param ZipArchive $archive
     * @param Path|null  $relativePath
     * @param File[]     ...$files
     */
    private function populateArchive(ZipArchive $archive, Path $relativePath = null, File ...$files)
    {
        foreach ($files as $file) {
            $fileName = $file->getPath()->getFileName();
            $path = null === $relativePath ? new Path($fileName) : $relativePath->join($fileName);

            if ($file instanceof Directory) {
                if ($file->hasChildren()) {
                    $this->populateArchive($archive, $path, ...$file->getChildren());
                } else {
                    $archive->addEmptyDir($path);
                }
                continue;
            }

            $archive->addFile($file->getPath(), $path);
        }
    }
}
