<?php

use Engine\FileManager\Entity\Directory;
use Engine\FileManager\Entity\File;
use Engine\FileManager\Entity\Hyperlink;
use Engine\FileManager\View\DirectoryView;
use Engine\FileManager\View\FileView;
use Engine\FileManager\View\HyperlinkView;

return [
    File::class => FileView::class,
    Directory::class => DirectoryView::class,
    Hyperlink::class => HyperlinkView::class,
];
