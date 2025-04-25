<?php

namespace Engine\FileManager\Entity;

use ArrayIterator;
use IteratorAggregate;

/**
 * Class FileCollection.
 *
 * File collection entity implementation.
 */
class FileCollection implements IteratorAggregate
{
    /**
     * @var File[]
     */
    private $files = [];

    /**
     * @param File $file
     */
    public function addFile(File $file)
    {
        $this->files[] = $file;
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->files);
    }
}
