<?php

namespace Engine\FileManager\Value;

use InvalidArgumentException;

/**
 * Class FileId.
 *
 * File id entity implementation.
 */
class FileId
{
    /**
     * @var string
     */
    private $value;

    /**
     * @var FileId|null
     */
    private $parent;

    /**
     * FileId constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->setValue($value);
        $this->createParent();
    }

    public static function createFromPath(string $path)
    {
        return new self('/'.ltrim(str_replace(DIRECTORY_SEPARATOR, '/', $path), '/'));
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Checks if file id has parent id.
     *
     * @return bool
     */
    public function hasParent(): bool
    {
        return null !== $this->parent;
    }

    /**
     * @return FileId
     */
    public function getParent(): FileId
    {
        return $this->parent;
    }

    /**
     * Joins string to file id.
     *
     * @param string $value
     *
     * @return FileId
     */
    public function join(string $value): FileId
    {
        return new self(rtrim($this->getValue(), '/').'/'.ltrim($value, '/'));
    }

    /**
     * Checks if file id equals to string.
     *
     * @param string $id
     *
     * @return bool
     */
    public function isEqual(string $id): bool
    {
        return $this->getValue() === $id;
    }

    /**
     * Check if is storage root.
     *
     * @return bool
     */
    public function isRoot(): bool
    {
        return '/' === $this->getValue();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue();
    }

    /**
     * Checks if value for file id is valid.
     *
     * @param string $value
     *
     * @return bool
     */
    private function isValid(string $value): bool
    {
        return '/' === $value ||
            (
                preg_match('/^\/[^*?"|><\\\\]*[^*?"|><\/\\\\]$/iu', $value) &&
                !preg_match('/\/\.{1,2}$/iu', $value) &&
                !preg_match('/^\/\//iu', $value) &&
                !preg_match('/\/\.\.\//iu', $value)
            );
    }

    /**
     * @param string $value
     */
    private function setValue(string $value)
    {
        if (!$this->isValid($value)) {
            throw new InvalidArgumentException('Invalid file id was provided');
        }

        $this->value = $value;
    }

    /**
     * Creates parent id for current file id.
     */
    private function createParent()
    {
        if (!$this->isEqual('/')) {
            $this->parent = new self(
                '/'.ltrim(mb_substr($this->getValue(), 0, mb_strrpos($this->getValue(), '/')), '/')
            );
        }
    }
}
