<?php

namespace Engine\FileManager\Value;

/**
 * Class Pattern.
 *
 * Search pattern entity implementation.
 */
class Pattern
{
    /**
     * @var string
     */
    private $value;

    /**
     * Pattern constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->setValue($value);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getValue();
    }
}
