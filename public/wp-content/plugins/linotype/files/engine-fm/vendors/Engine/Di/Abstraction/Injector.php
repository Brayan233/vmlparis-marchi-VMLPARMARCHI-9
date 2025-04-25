<?php

namespace Engine\Di\Abstraction;

/**
 * Interface Injector.
 */
interface Injector
{
    /**
     * Creates an object using the list of services definitions.
     *
     * @param string $class
     * @param array  $args
     *
     * @return mixed
     */
    public function createObject(string $class, array $args = []);

    /**
     * Invokes an object method with arguments.
     *
     * @param object $object
     * @param string $name
     * @param array  $args
     *
     * @return mixed method result
     */
    public function invokeMethod($object, string $name, array $args = []);

    /**
     * Invokes a function with arguments.
     *
     * @param callable $function
     * @param array    $args
     *
     * @return mixed function result
     */
    public function invokeFunction(callable $function, array $args = []);

    /**
     * Sets a service definition.
     *
     * @param string                       $interface
     * @param string|array|object|callable $definition
     *
     * @return $this
     */
    public function setService(string $interface, $definition): self;

    /**
     * Returns a service object or null if service was not found.
     *
     * @param string $interface
     *
     * @return object|null
     */
    public function getService(string $interface);

    /**
     * Loads services definitions.
     *
     * @param array $definitions
     *
     * @return $this
     */
    public function loadServices(array $definitions): self;
}
