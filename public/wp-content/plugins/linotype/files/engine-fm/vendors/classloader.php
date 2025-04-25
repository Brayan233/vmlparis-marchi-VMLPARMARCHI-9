<?php

/**
 * Registers class autoloader compatible with PSR-4.
 * Root of classes equals current directory (__DIR__).
 */
spl_autoload_register(function ($class) {
    $file = __DIR__.DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';

    if (file_exists($file)) {
        require_once $file;
    }
});
