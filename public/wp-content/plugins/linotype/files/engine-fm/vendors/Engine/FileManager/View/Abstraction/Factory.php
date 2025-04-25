<?php

namespace Engine\FileManager\View\Abstraction;

use Engine\FileManager\View\DirectoryView;
use Engine\FileManager\View\FileView;
use Engine\FileManager\View\HyperlinkView;

/**
 * Interface Factory
 */
interface Factory
{
    /**
     * Create view which is corresponding entity.
     *
     * @param $raw
     *
     * @return FileView|DirectoryView|HyperlinkView|null|array
     */
    public function createView($raw);
}
