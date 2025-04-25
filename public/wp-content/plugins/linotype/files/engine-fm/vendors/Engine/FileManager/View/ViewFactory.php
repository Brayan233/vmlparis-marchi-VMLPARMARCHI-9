<?php

namespace Engine\FileManager\View;

use Engine\FileManager\View\Abstraction\Factory;
use Traversable;

/**
 * Class Factory.
 *
 * File view factory implementation.
 */
class ViewFactory implements Factory
{
    /**
     * @var array
     */
    private $views = [];

    /**
     * Factory constructor.
     *
     * @param array $views
     */
    public function __construct(array $views)
    {
        $this->views = $views;
    }

    /**
     * {@inheritdoc}
     */
    public function createView($raw)
    {
        if (null === $raw || is_array($raw)) {
            return $raw;
        }

        if ($raw instanceof Traversable) {
            $result = [];
            foreach ($raw as $entity) {
                $result[] = new $this->views[get_class($entity)]($entity, $this);
            }

            return $result;
        }

        return new $this->views[get_class($raw)]($raw, $this);
    }
}
