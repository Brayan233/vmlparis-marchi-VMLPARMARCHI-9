<?php

namespace Engine\FileManager\Entity;

use Engine\Http\Value\Url;

/**
 * Class Hyperlink.
 *
 * Hyperlink entity implementation.
 */
class Hyperlink extends File
{
    /**
     * @var Url|null
     */
    private $url;

    /**
     * @return Url|null
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param Url|null $url
     *
     * @return Hyperlink
     */
    public function setUrl(Url $url): self
    {
        $this->url = $url;

        return $this;
    }
}
