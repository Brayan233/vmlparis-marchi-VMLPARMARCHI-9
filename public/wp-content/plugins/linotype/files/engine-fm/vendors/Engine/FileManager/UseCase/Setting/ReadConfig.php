<?php

namespace Engine\FileManager\UseCase\Setting;

use Engine\FileManager\Setting\Contract\AppConfig;
use Engine\Utility\Abstraction\Config;

/**
 * Class ReadConfig.
 *
 * Service to read an application config.
 */
class ReadConfig
{
    /**
     * @var AppConfig
     */
    private $config;

    /**
     * ReadConfig constructor.
     *
     * @param AppConfig $config
     */
    public function __construct(AppConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Reads an application config.
     *
     * @return Config
     */
    public function execute(): Config
    {
        return $this->config->readPublicConfig();
    }
}
