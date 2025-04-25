<?php

namespace Engine\FileManager\View;

use Engine\FileManager\Entity\Directory;
use Engine\FileManager\Value\Pattern;
use Engine\FileManager\View\Abstraction\Factory;

/**
 * Class DirectoryView.
 *
 * Directory entity representation for server application response.
 */
class DirectoryView extends FileView
{
    /**
     * @var FileView[]|null
     */
    private $children;

    /**
     * @var Pattern|null
     */
    private $pattern;

    /**
     * DirectoryView constructor.
     *
     * @param Directory $directory
     * @param Factory   $factory
     */
    public function __construct(Directory $directory, Factory $factory)
    {
        parent::__construct($directory);

        $this->pattern = $directory->getPattern();

        if ($directory->hasChildren()) {
            foreach ($directory->getChildren() as $child) {
                $this->children[] = $factory->createView($child);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
                'extension' => 'dir',
                'children' => $this->getChildren(),
                'pattern' => null !== $this->getPattern() ? $this->getPattern()->getValue() : null,
            ] + parent::jsonSerialize();
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return 'directory';
    }

    /**
     * @return FileView[]|null
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return Pattern|null
     */
    public function getPattern()
    {
        return $this->pattern;
    }
}
