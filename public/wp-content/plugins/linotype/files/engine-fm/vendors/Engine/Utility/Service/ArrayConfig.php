<?php

namespace Engine\Utility\Service;

use Engine\Utility\Abstraction\Config;

/**
 * Class ArrayConfig.
 *
 * Array configuration implementation.
 */
class ArrayConfig implements Config
{
    /**
     * @var array
     */
    private $config;

    /**
     * ArrayConfig constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key, $defaultValue = null)
    {
        return $this->config[$key] ?? $defaultValue;
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, $value)
    {
        $this->config[$key] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function asArray(): array
    {
        return $this->config;
    }
}
