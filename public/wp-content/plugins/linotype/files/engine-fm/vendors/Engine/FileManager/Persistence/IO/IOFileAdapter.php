<?php

namespace Engine\FileManager\Persistence\IO;

use Engine\FileManager\Persistence\Contract\FileAdapter;
use Engine\FileManager\Persistence\IO\Exception\IONotFoundException;
use Engine\FileManager\Setting\Contract\AppConfig;
use Engine\FileManager\Value\FileId;
use Engine\FileManager\Value\Path;
use RuntimeException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use stdClass;
use Throwable;

/**
 * Class IOFileAdapter.
 *
 * IO system file adapter implementation.
 */
class IOFileAdapter implements FileAdapter
{
    /**
     * @var Path
     */
    private $storagePath;

    /**
     * IOFileAdapter constructor.
     *
     * @param AppConfig $config
     */
    public function __construct(AppConfig $config)
    {
        $this->storagePath = $config->getStoragePath();
    }

    /**
     * {@inheritdoc}
     */
    public function readFile(string $id, bool $recursively = null): stdClass
    {
        $path = $this->getPathById($id);

        if (!file_exists($path) || !is_readable($path)) {
            throw new IONotFoundException(
                sprintf('File or directory \'%s\' is not exists or it\'s not readable', $path)
            );
        }

        $raw = new stdClass();
        $raw->id = $id;
        $raw->path = $path;
        $raw->size = $this->getSize($path);
        $raw->type = $this->getType($path);
        $raw->permissions = $this->getPermissions($path);
        $raw->lastModified = $this->getLastModified($path);
        $raw->isHyperlink = in_array($path->getFileName()->getExtension(), ['http', 'https', 'ftp', 'sftp']);
        $raw->isDirectory = is_dir($path);

        if ($raw->isHyperlink) {
            $contents = file_get_contents($raw->path);
            if (false === file_get_contents($raw->path)) {
                throw new RuntimeException(sprintf('Error occurred during reading hyperlink \'%s\'', $raw->id));
            }

            $raw->url = $contents;
        }

        if ($raw->isDirectory) {
            $raw->children = null;
            if (null !== $recursively) {
                $raw->children = [];
                foreach ($this->scanDirectory($path) as $fileName) {
                    $childId = $this->joinId($id, $fileName);
                    $childPath = $this->getPathById($childId);
                    if (!$this->isHiddenFile($childPath) && is_readable($childPath)) {
                        $raw->children[] = $this->readFile($childId, $recursively ?: null);
                    }
                }
            }
            $raw->hasChildren = !empty($raw->children);
        }

        return $raw;
    }

    /**
     * {@inheritdoc}
     */
    public function searchFiles(string $id, string $pattern): array
    {
        $path = $this->getPathById($id);
        $children = $this->scanDirectory($path);
        $rawList = [];

        foreach ($children as $fileName) {
            $childPath = $path.DIRECTORY_SEPARATOR.$fileName;

            if (!is_readable($childPath)) {
                continue;
            }

            $childId = $this->getIdByPath($childPath);

            if (fnmatch($pattern, strtolower($fileName)) && !$this->isHiddenFile($childPath)) {
                $rawList[] = $this->readFile($childId);
            }

            if (is_dir($childPath)) {
                $rawList = array_merge($rawList, $this->searchFiles($childId, $pattern));
            }
        }

        return $rawList;
    }

    /**
     * {@inheritdoc}
     */
    public function saveFile(stdClass $raw)
    {
        $path = $this->getPathById($raw->id);

        if (!file_exists($path)) {
            if ($raw->isDirectory) {
                $errorMessage = sprintf(
                    'Error occurred during creating directory \'%s\'. Please check permissions.',
                    $raw->id
                );
                try {
                    if (!mkdir($path)) {
                        throw new RuntimeException($errorMessage);
                    }
                } catch (Throwable $throwable) {
                    throw new RuntimeException($errorMessage);
                }
            }

            if ($raw->isHyperlink) {
                if (false === file_put_contents($path, $raw->url)) {
                    throw new RuntimeException(sprintf('Error occurred during write hyperlink \'%s\'', $raw->id));
                }
            }
        }

        if (null !== $raw->path) {
            $this->setPath($path, $raw->path);
            $path = $raw->path;
            $raw->id = $this->getIdByPath($raw->path);
        }

        if (null !== $raw->permissions) {
            $this->setPermissions($path, $raw->permissions);
        }

        if (null !== $raw->lastModified) {
            $this->setLastModified($path, $raw->lastModified);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function copyFile(stdClass $raw)
    {
        $source = $this->getPathById($raw->id);
        $destination = $raw->path;

        if ($source->isEqual($destination)) {
            return;
        }

        if (is_link($source)) {
            if (!symlink(readlink($source), $destination)) {
                throw new RuntimeException(sprintf('Error occurred during copying symlink \'%s\'', $raw->id));
            }
        } elseif (is_file($source)) {
            if (!copy($source, $destination)) {
                throw new RuntimeException(sprintf('Error occurred during copying file \'%s\'', $raw->id));
            }
        } elseif (!is_dir($destination)) {
            $errorMessage = sprintf(
                'Error occurred during copying directory \'%s\'. Please check permissions.',
                $raw->id
            );
            try {
                if (!mkdir($destination)) {
                    throw new RuntimeException($errorMessage);
                }
            } catch (Throwable $throwable) {
                throw new RuntimeException($errorMessage);
            }
        }

        $raw->id = $this->getIdByPath($raw->path);
        $this->saveFile($raw);

        if ($raw->isDirectory && $raw->hasChildren) {
            foreach ($raw->children as $child) {
                $this->copyFile($child);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function moveFile(stdClass $raw)
    {
        $sourceId = $raw->id;
        $source = $this->getPathById($sourceId);
        $destination = $raw->path;

        if ($source->isEqual($destination)) {
            return;
        }

        $this->copyFile($raw);
        $this->removeFile($sourceId);
    }

    /**
     * {@inheritdoc}
     */
    public function removeFile(string $id)
    {
        $path = $this->getPathById($id);

        if (is_dir($path)) {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($iterator as $item) {
                if ($item->isDir()) {
                    if (!rmdir($item->getRealPath())) {
                        throw new RuntimeException(
                            sprintf(
                                'Error occurred during removing directory \'%s\'',
                                $this->getIdByPath($item->getRealPath())
                            )
                        );
                    }
                } else {
                    if (!unlink($item->getRealPath())) {
                        throw new RuntimeException(
                            sprintf(
                                'Error occurred during removing file \'%s\'',
                                $this->getIdByPath($item->getRealPath())
                            )
                        );
                    }
                }
            }
            if (!rmdir($path)) {
                throw new RuntimeException(
                    sprintf('Error occurred during removing directory \'%s\'', $id)
                );
            }

            return;
        }

        if (!unlink($path)) {
            throw new RuntimeException(sprintf('Error occurred during removing file \'%s\'', $id));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function exists(string $id): bool
    {
        return file_exists($this->getPathById($id));
    }

    /**
     * {@inheritdoc}
     */
    public function getFileSize(string $id): int
    {
        return $this->getSize($this->getPathById($id));
    }

    /**
     * Scans directory.
     *
     * @param string $path
     *
     * @return array
     */
    private function scanDirectory(string $path): array
    {
        $contents = scandir($path);

        if (false === $contents) {
            throw new RuntimeException(sprintf('Cannot read directory \'%s\'', $this->getIdByPath($path)));
        }

        return array_diff($contents, ['..', '.']);
    }

    /**
     * Returns file size.
     *
     * @param string $path
     *
     * @return bool|int|string
     */
    private function getSize(string $path)
    {
        if (is_file($path)) {
            return filesize($path);
        }

        if ($this->isWindows()) {
            if (class_exists('\COM')) {
                $com = new \COM('scripting.filesystemobject');
                $size = $com->getfolder($path)->size;
                $com = null;
            } else {
                $size = 0;
            }
        } else {
            $resource = popen('/usr/bin/du -sk "'.$path.'"', 'r');
            $size = fgets($resource, 4096);
            $size = substr($size, 0, strpos($size, "\t"));
            pclose($resource);
        }

        return $size;
    }

    /**
     * Returns file mime type.
     *
     * @param string $path
     *
     * @return string
     */
    private function getType(string $path)
    {
        return mime_content_type($path);
    }

    /**
     * Renames file.
     *
     * @param string $path
     * @param string $newPath
     */
    private function setPath(string $path, string $newPath)
    {
        if ($path !== $newPath) {
            if (!rename($path, $newPath)) {
                throw new RuntimeException(sprintf(
                    'Error occurred during updating file \'%s\'',
                    $this->getIdByPath($path)
                ));
            }
        }
    }

    /**
     * Return file permissions.
     *
     * @param string $path
     *
     * @return number
     */
    private function getPermissions(string $path)
    {
        return octdec('0'.substr(sprintf('%o', fileperms($path)), -3));
    }

    /**
     * Sets file permissions.
     *
     * @param string $path
     * @param int    $mode
     */
    private function setPermissions(string $path, int $mode)
    {
        $octView = '0'.decoct($mode & 0777);

        if (!$this->isWindows()) {
            exec('chmod -R '.escapeshellarg($octView).' '.$path);
        }
    }

    /**
     * Returns file last modified time.
     *
     * @param string $path
     *
     * @return bool|int
     */
    private function getLastModified(string $path)
    {
        return filemtime($path);
    }

    private function setLastModified(string $path, int $timestamp)
    {
        if (!is_dir($path) && file_exists($path)) {
            if (!touch($path, $timestamp)) {
                throw new RuntimeException(sprintf(
                    'Error occurred during updating file \'%s\'',
                    $this->getIdByPath($path)
                ));
            }
        }
    }

    /**
     * Returns file path by id.
     *
     * @param string $id
     *
     * @return Path
     */
    private function getPathById(string $id): Path
    {
        return $this->storagePath->join($id);
    }

    /**
     * Returns id by file path.
     *
     * @param string $path
     *
     * @return string
     */
    private function getIdByPath(string $path): string
    {
        return FileId::createFromPath($this->storagePath->subPath($path))->getValue();
    }

    /**
     * Concatenates id with value.
     *
     * @param string $id
     * @param string $value
     *
     * @return string
     */
    private function joinId(string $id, string $value)
    {
        return rtrim($id, '/').'/'.ltrim($value, '/');
    }

    /**
     * Checks if file is hidden.
     *
     * @param string $path
     *
     * @return bool
     */
    private function isHiddenFile(string $path): bool
    {
        return '$' === mb_substr($path, mb_strrpos($path, DIRECTORY_SEPARATOR) + 1, 1);
    }

    /**
     * Checks if it is Windows environment.
     *
     * @return bool
     */
    private function isWindows(): bool
    {
        return 'WIN' === strtoupper(substr(PHP_OS, 0, 3));
    }
}
