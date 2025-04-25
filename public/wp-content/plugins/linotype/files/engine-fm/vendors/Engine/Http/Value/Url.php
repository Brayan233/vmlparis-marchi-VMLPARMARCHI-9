<?php

namespace Engine\Http\Value;

use InvalidArgumentException;

/**
 * Class Url.
 *
 * HTTP Url implementation.
 */
class Url
{
    /**
     * @var string|null
     */
    private $scheme;

    /**
     * @var string|null
     */
    private $user;

    /**
     * @var string|null
     */
    private $password;

    /**
     * @var string|null
     */
    private $host;

    /**
     * @var int|null
     */
    private $port;

    /**
     * @var string|null
     */
    private $basePath;

    /**
     * @var string|null
     */
    private $path;

    /**
     * @var string|null
     */
    private $query;

    /**
     * @var string|null
     */
    private $fragment;

    /**
     * @var string|null
     */
    private $asString;

    /**
     * Url constructor.
     *
     * @param string      $url
     * @param string|null $basePath
     */
    public function __construct(string $url, string $basePath = null)
    {
        $partials = parse_url($url);
        $basePath = trim($basePath, '/');

        $this->scheme = isset($partials['scheme']) ? $partials['scheme'] : null;
        $this->user = isset($partials['user']) ? $partials['user'] : null;
        $this->password = isset($partials['pass']) ? $partials['pass'] : null;
        $this->host = isset($partials['host']) ? $partials['host'] : null;
        $this->port = isset($partials['port']) ? (int) $partials['port'] : null;
        $this->query = isset($partials['query']) ? $partials['query'] : null;
        $this->fragment = isset($partials['fragment']) ? $partials['fragment'] : null;

        if ($basePath) {
            $this->basePath = '/'.$basePath;
            if (isset($partials['path'])) {
                $path = '/'.trim($partials['path'], '/');
                if (substr($path, 0, strlen($this->basePath)) !== $this->basePath ||
                    isset($path[strlen($this->basePath)]) && '/' !== $path[strlen($this->basePath)]
                ) {
                    throw new InvalidArgumentException('Invalid basePath, it must be part of the path');
                }
                $path = substr($path, strlen($this->basePath));
                $this->path = '/'.trim($path, '/');
            } else {
                $this->path = '/';
            }
        } else {
            $this->path = isset($partials['path']) ? '/'.trim($partials['path'], '/') : '/';
        }
    }

    /**
     * @return string|null
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * @param string $scheme
     *
     * @return $this
     */
    public function setScheme(string $scheme): self
    {
        $this->scheme = $scheme;
        $this->asString = null;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $user
     *
     * @return $this
     */
    public function setUser(string $user): self
    {
        $this->user = $user;
        $this->asString = null;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        $this->asString = null;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     *
     * @return $this
     */
    public function setHost(string $host): self
    {
        $this->host = $host;
        $this->asString = null;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param int $port
     *
     * @return $this
     */
    public function setPort(int $port): self
    {
        $this->port = $port;
        $this->asString = null;

        return $this;
    }

    /**
     * @param string $basePath
     *
     * @return $this
     */
    public function setBasePath(string $basePath): self
    {
        $this->basePath = '/'.trim($basePath, '/');
        $this->asString = null;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * @return string|null
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setPath(string $path): self
    {
        $this->path = '/'.trim($path, '/');
        $this->asString = null;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param string $query
     *
     * @return $this
     */
    public function setQuery($query): self
    {
        $this->query = $query;
        $this->asString = null;

        return $this;
    }

    /**
     * @return array
     */
    public function getQueryParams()
    {
        if (null === $this->query) {
            return [];
        }

        parse_str($this->query, $params);

        return $params;
    }

    /**
     * @param array $params
     *
     * @return $this
     */
    public function setQueryParams(array $params): self
    {
        $this->query = empty($params) ? null : http_build_query($params);
        $this->asString = null;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * @param string $fragment
     *
     * @return $this
     */
    public function setFragment(string $fragment): self
    {
        $this->fragment = $fragment;
        $this->asString = null;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return (string) $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if (null !== $this->asString) {
            return $this->asString;
        }

        $this->asString = '';

        if (null !== $this->scheme) {
            $this->asString .= $this->scheme.'://';
        }
        if (null !== $this->user) {
            $this->asString .= $this->user;
        }
        if (null !== $this->password && null !== $this->user) {
            $this->asString .= ':'.$this->password;
        }
        if (null !== $this->host) {
            $this->asString .= null === $this->user ? $this->host : '@'.$this->host;
        }
        if (null !== $this->port && null !== $this->host) {
            $this->asString .= ':'.$this->port;
        }
        $this->asString .= rtrim($this->basePath, '/');
        $this->asString .= $this->path;
        if (null !== $this->query) {
            $this->asString .= '?'.$this->query;
        }
        if (null !== $this->fragment) {
            $this->asString .= '#'.$this->fragment;
        }

        return $this->asString;
    }
}
