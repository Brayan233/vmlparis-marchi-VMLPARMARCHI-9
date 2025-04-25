<?php

namespace Engine\FileManager\Entity;

use Engine\FileManager\Value\Pattern;

/**
 * Class Directory.
 *
 * Directory entity implementation.
 */
class Directory extends File
{
    /**
     * @var File[]|null
     */
    private $children;

    /**
     * @var Pattern|null
     */
    private $pattern;

    /**
     * @return File[]|null
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return bool
     */
    public function hasChildren(): bool
    {
        return !empty($this->children);
    }

    /**
     * @param File[] $files
     *
     * @return Directory
     */
    public function setChildren(File ...$files): self
    {
        $this->children = $files;

        return $this;
    }

    /**
     * @param File $child
     *
     * @return Directory
     */
    public function addChild(File $child): self
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * @return Pattern|null
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @param Pattern|null $pattern
     *
     * @return Directory
     */
    public function setPattern(Pattern $pattern): self
    {
        $this->pattern = $pattern;

        return $this;
    }
}
