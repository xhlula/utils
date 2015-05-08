<?php

namespace maldoinc\utils\session;


class NativeSessionManager implements SessionManagerInterface
{
    protected $baseKey;

    public function __construct($base_key)
    {
        $this->baseKey = $base_key;

        if (!isset($_SESSION[$this->baseKey])) {
            $_SESSION[$this->baseKey] = [];
        }
    }

    public function set($key, $value)
    {
        $_SESSION[$this->baseKey][$key] = $value;
    }

    public function get($key, $default = null)
    {
        return $this->has($key) ? $_SESSION[$this->baseKey][$key] : $default;
    }

    public function forget($key)
    {
        unset($_SESSION[$this->baseKey][$key]);
    }

    public function pull($key, $default = null)
    {
        $val = $this->get($key, $default);
        $this->forget($key);

        return $val;
    }

    public function flush()
    {
        $_SESSION[$this->baseKey] = [];
    }

    public function all()
    {
        return $_SESSION[$this->baseKey];
    }

    public function has($key)
    {
        return isset($_SESSION[$this->baseKey][$key]);
    }
}