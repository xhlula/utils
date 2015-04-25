<?php

namespace maldoinc\utils\shopping;

class SessionPersistenceStrategy implements ShoppingCartPersistentInterface
{
    protected $key;

    public function __construct($key)
    {
        if (!session_id()) {
            session_start();
        }

        $this->key = $key;
    }

    /**
     * @return void
     */
    function clear()
    {
        unset($_SESSION[$this->key]);
    }

    function save($data)
    {
        $_SESSION[$this->key] = $data;
    }

    function load()
    {
        return isset($_SESSION[$this->key]) ? $_SESSION[$this->key] : null;
    }
}