<?php

namespace Engine\Http\Value;

/**
 * Class CgiUrl.
 *
 * Url composed of CGI environment data.
 */
class CgiUrl extends Url
{
    /**
     * CGIUrl constructor.
     */
    public function __construct()
    {
        parent::__construct('/'.$_GET['action']);
    }
}