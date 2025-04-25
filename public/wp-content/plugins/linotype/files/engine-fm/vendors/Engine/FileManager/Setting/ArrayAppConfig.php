<?php

namespace Engine\FileManager\Setting;

use DomainException;
use Engine\FileManager\Setting\Contract\AppConfig;
use Engine\FileManager\Value\Path;
use Engine\FileManager\Value\Size;
use Engine\Utility\Abstraction\Config;
use Engine\Utility\Service\ArrayConfig;
use Engine\Lang\Variable;

/**
 * Class ArrayAppConfig
 */
class ArrayAppConfig extends ArrayConfig implements AppConfig
{
    /**
     * @var Config
     */
    private $publicConfig;

    /**
     * @var Path
     */
    private $storagePath;

    /**
     * @var Path
     */
    private $temporaryPath;

    /**
     * @var Path
     */
    private $publicSettingsPath;

    /**
     * @var Size
     */
    private $storageMaxSize;

    /**
     * @var Size
     */
    private $uploadMaxFileSize;

    /**
     * @return Config
     */
    public function readPublicConfig(): Config
    {
        if (null === $this->publicConfig) {
            $this->publicConfig = new ArrayConfig(
                require realpath($this->getPublicSettingsPath())
            );
        }

        return $this->publicConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function writePublicConfig(Config $config)
    {
        $data = '<?php return '.Variable::export($config->asArray()).'; ?>';

        file_put_contents($this->getPublicSettingsPath(), $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getStoragePath(): Path
    {
        if (null === $this->storagePath) {
            $storagePath = realpath($this->get('storagePath'));
            if (false === $storagePath) {
                throw new DomainException('Storage path does not exist. Please check settings.');
            }
            $this->storagePath = new Path($storagePath, true);
        }

        return $this->storagePath;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemporaryPath(): Path
    {
        if (null === $this->temporaryPath) {
            $temporaryPath = realpath($this->get('temporaryPath'));
            if (false === $temporaryPath) {
                throw new DomainException('Temporary path does not exist. Please check settings.');
            }
            $this->temporaryPath = new Path($temporaryPath, true);
        }

        return $this->temporaryPath;
    }

    /**
     * {@inheritdoc}
     */
    public function getStorageMaxSize(): Size
    {
        if (null === $this->storageMaxSize) {
            $this->storageMaxSize = new Size($this->get('storageMaxSize', 0));
        }

        return $this->storageMaxSize;
    }

    /**
     * {@inheritdoc}
     */
    public function getUploadMaxFileSize(): Size
    {
        if (null === $this->uploadMaxFileSize) {
            $this->uploadMaxFileSize = new Size($this->get('uploadMaxFileSize', 0));
        }

        return $this->uploadMaxFileSize;
    }

    /**
     * {@inheritdoc}
     */
    public function isFileSizeAllowed(int $size): bool
    {
        return $this->getUploadMaxFileSize()->getValue() >= $size;
    }

    /**
     * {@inheritdoc}
     */
    public function isMimeTypeAllowed(string $type): bool
    {
        if (is_array($this->get('allowMimeTypes'))) {
            return in_array($type, $this->get('allowMimeTypes'));
        }

        if (is_array($this->get('denyMimeTypes'))) {
            return !in_array($type, $this->get('denyMimeTypes'));
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    private function getPublicSettingsPath(): Path
    {
        if (null === $this->publicSettingsPath) {
            $settingsPath = realpath($this->get('settingsPath'));
            if (false === $settingsPath) {
                throw new DomainException('Settings path does not exist. Please check settings.');
            }
            $settingsPath = new Path($settingsPath, true);
            $this->publicSettingsPath = $settingsPath->join('public.php');
        }

        return $this->publicSettingsPath;
    }
}
