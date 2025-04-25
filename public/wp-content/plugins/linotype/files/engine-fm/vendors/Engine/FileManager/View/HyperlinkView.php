<?php

namespace Engine\FileManager\View;

use Engine\Http\Value\Url;

/**
 * Class HyperlinkView.
 *
 * Hyperlink entity representation for server application response.
 */
class HyperlinkView extends FileView
{
    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
                'url' => (string) $this->getUrl(),
            ] + parent::jsonSerialize();
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return 'hyperlink';
    }

    /**
     * @return Url|null
     */
    public function getUrl()
    {
        return $this->file->getUrl();
    }
}
