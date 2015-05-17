<?php

namespace maldoinc\utils\session;


class SessionManager implements SessionManagerInterface
{
    protected $baseKey;
    protected $sess;

    public function __construct(&$sess, $base_key)
    {
        if (!isset($sess[$base_key])) {
            $sess[$base_key] = array();
        }

        $this->sess = &$sess[$base_key];
    }

    public function set($key, $value)
    {
        $this->sess[$key] = $value;
    }

    public function get($key, $default = null)
    {
        return $this->has($key) ? $this->sess[$key] : $default;
    }

    public function forget($key)
    {
        unset($this->sess[$key]);
    }

    public function pull($key, $default = null)
    {
        $val = $this->get($key, $default);
        $this->forget($key);

        return $val;
    }

    public function flush()
    {
        $this->sess = [];
    }

    public function all()
    {
        return $this->sess;
    }

    public function has($key)
    {
        return isset($this->sess[$key]);
    }
}