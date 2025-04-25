<?php

namespace Engine\FileManager\Setting\Contract;

use Engine\FileManager\Value\Path;
use Engine\FileManager\Value\Size;
use Engine\Utility\Abstraction\Config;

/**
 * Interface Config.
 *
 * Application configuration interface.
 */
interface AppConfig extends Config
{
    /**
     * Reads public configuration.
     * Config accessible to client application.
     *
     * @return Config
     */
    public function readPublicConfig(): Config;

    /**
     * Writes public configuration.
     * Config accessible to client application.
     *
     * @param Config $config
     *
     * @return mixed
     */
    public function writePublicConfig(Config $config);

    /**
     * Returns storage path.
     *
     * @return Path
     */
    public function getStoragePath(): Path;

    /**
     * Returns temporary path.
     *
     * @return Path
     */
    public function getTemporaryPath(): Path;

    /**
     * Returns storage max size.
     *
     * @return Size
     */
    public function getStorageMaxSize(): Size;

    /**
     * Returns upload max file size.
     *
     * @return Size
     */
    public function getUploadMaxFileSize(): Size;

    /**
     * Checks if file size is allowed.
     *
     * @param int $size
     *
     * @return bool
     */
    public function isFileSizeAllowed(int $size): bool;

    /**
     * Checks if file mime type is allowed.
     *
     * @param string $type
     *
     * @return bool
     */
    public function isMimeTypeAllowed(string $type): bool;
}
