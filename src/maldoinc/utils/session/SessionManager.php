<?php

namespace maldoinc\utils\session;


class SessionManager implements SessionManagerInterface
{
    // protected constants are not possible as of yet, so this will do till then
    protected $DELIMITER = '.';

    protected $sess;

    public function __construct(&$sess, $base_key)
    {
        // initialize the assigned key to an array
        if (!(isset($sess[$base_key]) && is_array($sess[$base_key]))) {
            $sess[$base_key] = array();
        }

        $this->sess = &$sess[$base_key];
    }

    public function set($key, $value)
    {
        $sess = &$this->navigate($key);

        $sess[$this->getName($key)] = $value;
    }

    /**
     * Navigate to the specified key.
     *
     * Dot denotes a "namespace"
     *
     * @param $key
     * @return mixed
     */
    protected function &navigate($key)
    {
        $sess = &$this->sess;
        $parts = $this->getParts($key);

        foreach ($parts as $p) {
            $sess = &$sess[$p];
        }

        return $sess;
    }

    /**
     * Get all the "namespaces" from the specified key
     *
     * @param $key
     * @return array
     */
    protected function getParts($key)
    {
        $arr = explode($this->DELIMITER, $key);
        array_pop($arr);

        return $arr;
    }

    /**
     * Get the property being accessed from the key (everything from the last dot, if any, til the end)
     *
     * @param $key
     * @return mixed
     */
    protected function getName($key)
    {
        $result = strrchr($key, $this->DELIMITER);

        // if dot is not found return key itself,
        // else remove dot from the returned string and return it
        return $result === false ? $key : substr($result, 1);
    }

    public function pull($key, $default = null)
    {
        $val = $this->get($key, $default);
        $this->forget($key);

        return $val;
    }

    public function get($key, $default = null)
    {
        $sess = &$this->navigate($key);

        return $this->has($key) ? $sess[$this->getName($key)] : $default;
    }

    public function has($key)
    {
        $sess = &$this->navigate($key);

        return isset($sess[$this->getName($key)]);
    }

    public function forget($key)
    {
        $sess = &$this->navigate($key);

        unset($sess[$key]);
    }

    public function flush()
    {
        $this->sess = array();
    }

    public function all()
    {
        return $this->sess;
    }
}