<?php

namespace Engine\Utility\Abstraction;

/**
 * Interface Config.
 *
 * Base configuration interface.
 */
interface Config
{
    /**
     * @param string $key
     * @param null   $defaultValue
     *
     * @return mixed
     */
    public function get(string $key, $defaultValue = null);

    /**
     * @param string $key
     * @param $value
     *
     * @return mixed
     */
    public function set(string $key, $value);

    /**
     * @return array
     */
    public function asArray(): array;
}
