<?php

namespace maldoinc\utils\session;


class SessionManager implements SessionManagerInterface
{
    // protected constants are not possible as of yet, so this will do till then
    protected $DELIMITER = '.';

    protected $sess;

    /**
     * Creates a new SessionManager object with a reference to $sess and a index pointed at $base_key
     * If the index at $base_key doesn't exist it will be initialized to an empty array
     *
     * @param array $sess Array passed by reference to be modified by SessionManager
     * @param string $base_key a subkey of the $sess variable where the data will be stored
     */
    public function __construct(&$sess, $base_key)
    {
        // initialize the assigned key to an array
        if (!(isset($sess[$base_key]) && is_array($sess[$base_key]))) {
            $sess[$base_key] = array();
        }

        $this->sess = &$sess[$base_key];
    }

    /**
     * Get the value at the specific key and then remove the key
     *
     * @param $key
     * @param $default
     * @return mixed
     */
    public function pull($key, $default = null)
    {
        $val = $this->get($key, $default);
        $this->remove($key);

        return $val;
    }

    /**
     * Get a key from the session
     *
     * @param $key
     * @param $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $sess = &$this->navigate($key);
        $property = $this->getName($key);

        return $this->itemHasProperty($sess, $property) ? $this->itemGetValue($sess, $property) : $default;
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
            if (is_object($sess)) {
                $sess = &$sess->$p;
            } else {
                $sess = &$sess[$p];
            }
        }

        return $sess;
    }

    /**
     * Get all the "namespaces" from the specified key.
     *
     * "greet.hello.world" will return ["gret", "hello"]
     *
     * @param $key
     * @return array
     */
    protected function getParts($key)
    {
        $arr = explode($this->DELIMITER, $key);
        // the last item is the name itself so we remove that from the resulting array
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

    protected function itemHasProperty($item, $property)
    {
        if (is_object($item)) {
            return property_exists($item, $property);
        }

        return isset($item[$property]);
    }

    protected function itemGetValue($item, $property)
    {
        if (is_object($item)) {
            return $item->$property;
        }

        return $item[$property];
    }

    /**
     * Remove a key from the session
     *
     * @param $key
     */
    public function remove($key)
    {
        $sess = &$this->navigate($key);
        $prop = $this->getName($key);

        if (is_object($sess)) {
            unset($sess->$prop);
        } else {
            unset($sess[$prop]);
        }
    }

    /**
     * Check key existence
     *
     * @param $key
     * @return mixed
     */
    public function has($key)
    {
        return $this->itemHasProperty($this->navigate($key), $this->getName($key));
    }

    /**
     * Set the key to a specific value
     *
     * @param mixed $key
     * @param $value
     */
    public function set($key, $value)
    {
        $sess = &$this->navigate($key);
        $prop = $this->getName($key);

        if (is_object($sess)) {
            $sess->$prop = $value;
        } else {
            $sess[$prop] = $value;
        }
    }

    /**
     * Clear the session
     *
     * @return mixed
     */
    public function clear()
    {
        $this->sess = array();
    }

    /**
     * Get all stored values
     *
     * @return mixed
     */
    public function all()
    {
        return $this->sess;
    }
}