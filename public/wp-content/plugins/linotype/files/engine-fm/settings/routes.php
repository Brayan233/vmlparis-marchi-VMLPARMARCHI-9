<?php

use Engine\FileManager\Action\Copy;
use Engine\FileManager\Action\CreateDirectory;
use Engine\FileManager\Action\CreateHyperlink;
use Engine\FileManager\Action\Download;
use Engine\FileManager\Action\Move;
use Engine\FileManager\Action\Open;
use Engine\FileManager\Action\Read;
use Engine\FileManager\Action\Remove;
use Engine\FileManager\Action\Rename;
use Engine\FileManager\Action\Search;
use Engine\FileManager\Action\SetPermissions;
use Engine\FileManager\Action\Trash;
use Engine\FileManager\Action\Upload;
use Engine\FileManager\Action\ReadConfig;

/*
 * List of routes and corresponding action classes.
 */
return [
    null => 'default action class',
    '/config' => ReadConfig::class,
    '/read' => Read::class,
    '/search' => Search::class,
    '/create/directory' => CreateDirectory::class,
    '/create/hyperlink' => CreateHyperlink::class,
    '/upload' => Upload::class,
    '/download' => Download::class,
    '/open' => Open::class,
    '/rename' => Rename::class,
    '/copy' => Copy::class,
    '/move' => Move::class,
    '/trash' => Trash::class,
    '/remove' => Remove::class,
    '/permissions' => SetPermissions::class,
];
