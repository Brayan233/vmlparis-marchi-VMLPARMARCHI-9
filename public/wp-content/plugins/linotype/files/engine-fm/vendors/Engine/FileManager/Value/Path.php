<?php

namespace Engine\FileManager\Value;

use InvalidArgumentException;

/**
 * Class Path.
 *
 * File path entity implementation.
 */
class Path
{
    /**
     * @var string
     */
    private $value;

    /**
     * @var Path|null
     */
    private $parent;

    /**
     * @var FileName
     */
    private $fileName;

    /**
     * @var bool
     */
    private $isDirectory;

    /**
     * Path constructor.
     *
     * @param string $value
     * @param bool   $isDirectory
     */
    public function __construct(string $value, bool $isDirectory = false)
    {
        $this->setValue($value, $isDirectory);
        $this->createParent();
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return FileName
     */
    public function getFileName(): FileName
    {
        return $this->fileName;
    }

    /**
     * Checks if file path has parent path.
     *
     * @return bool
     */
    public function hasParent(): bool
    {
        return null !== $this->parent;
    }

    /**
     * @return Path
     */
    public function getParent(): Path
    {
        return $this->parent;
    }

    /**
     * Checks if file path equals to string.
     *
     * @param string $path
     *
     * @return bool
     */
    public function isEqual(string $path): bool
    {
        return $this->getValue() === $path;
    }

    /**
     * Checks if file path is corresponded of directory.
     *
     * @return bool
     */
    public function isDirectory(): bool
    {
        return $this->isDirectory;
    }

    /**
     * Joins string to file path.
     *
     * @param string $value
     * @param bool   $isDirectory
     *
     * @return Path
     */
    public function join(string $value, bool $isDirectory = false): Path
    {
        return new self(rtrim($this->getValue(), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.
            ltrim($this->filter($value), DIRECTORY_SEPARATOR), $isDirectory);
    }

    public function subPath(string $value, bool $isDirectory = false)
    {
        return new self(ltrim(
            mb_substr($this->filter($value), mb_strlen($this->getValue())),
            DIRECTORY_SEPARATOR
        ), $isDirectory);
    }

    /**
     * Checks if current file path is part of the parent path.
     *
     * @param string $parentPath
     *
     * @return bool
     */
    public function within(string $parentPath): bool
    {
        return mb_substr($this->value, 0, mb_strlen($parentPath)) === $parentPath;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue();
    }

    /**
     * Adjusts the value.
     *
     * @param string $value
     *
     * @return string
     */
    private function filter(string $value): string
    {
        $value = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $value);
        if (mb_strlen($value) > 1 && DIRECTORY_SEPARATOR === mb_substr($value, -1)) {
            $value = mb_substr($value, 0, -1);
        }

        return $value;
    }

    /**
     * Checks if value for file path is valid.
     *
     * @param string $value
     *
     * @return bool
     */
    private function isValid(string $value): bool
    {
        return preg_match('/[^*?"|><]/iu', $value) &&
            !preg_match('/\\'.DIRECTORY_SEPARATOR.'{2,}/iu', $value) &&
            !preg_match('/^(\.\.|\.\.\\'.DIRECTORY_SEPARATOR.')/iu', $value) &&
            !preg_match('/(\\'.DIRECTORY_SEPARATOR.'\.\.|\.\.\\'.DIRECTORY_SEPARATOR.')/iu', $value) &&
            !preg_match('/\\'.DIRECTORY_SEPARATOR.'\.{1,2}$/iu', $value);
    }

    /**
     * @param string $value
     * @param bool   $isDirectory
     */
    private function setValue(string $value, bool $isDirectory)
    {
        $value = $this->filter($value);

        if (!$this->isValid($value)) {
            throw new InvalidArgumentException('Invalid path was provided');
        }

        $this->value = $value;
        $this->isDirectory = $isDirectory;

        $position = mb_strrpos($value, DIRECTORY_SEPARATOR);
        $fileName = false === $position ? $value : (string) mb_substr($value, $position + 1);

        $this->fileName = new FileName($fileName, $isDirectory);
    }

    private function createParent()
    {
        $value = rtrim($this->getValue(), DIRECTORY_SEPARATOR);
        $parts = explode(DIRECTORY_SEPARATOR, $value);

        if ($value !== $parts[0] && count($parts) > 1) {
            unset($parts[count($parts) - 1]);
            $this->parent = new self(implode(DIRECTORY_SEPARATOR, $parts) ?: '/', true);
        }
    }
}
