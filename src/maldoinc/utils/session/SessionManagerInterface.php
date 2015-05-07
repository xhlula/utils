<?php

namespace maldoinc\utils\session;

interface SessionManagerInterface
{
    function get($key, $default = null);
    function set($key, $value);
    function forget($key);
    function pull($key);
    function flush();
    function all();
    function has($key);
}