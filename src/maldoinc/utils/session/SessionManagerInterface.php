<?php

namespace maldoinc\utils\session;

interface SessionManagerInterface
{
    /**
     * Get a key from the session
     *
     * @param $key
     * @param $default
     * @return mixed
     */
    function get($key, $default = null);

    /**
     * Set the key to a specific value
     *
     * @param mixed $key
     * @param $value
     */
    function set($key, $value);

    /**
     * Remove a key from the session
     *
     * @param $key
     */
    function forget($key);

    /**
     * Get the value at the specific key and then remove the key
     *
     * @param $key
     * @return mixed
     */
    function pull($key);

    /**
     * Clear the session
     *
     * @return mixed
     */
    function flush();

    /**
     * Get all stored values
     *
     * @return mixed
     */
    function all();

    /**
     * Check key existence
     *
     * @param $key
     * @return mixed
     */
    function has($key);
}