<?php

namespace maldoinc\utils\shopping\persistence;

class SessionPersistenceStrategy implements CartPersistentInterface
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
    public function clear()
    {
        unset($_SESSION[$this->key]);
    }

    public function save($data)
    {
        $_SESSION[$this->key] = $data;
    }

    public function load()
    {
        return isset($_SESSION[$this->key]) ? $_SESSION[$this->key] : null;
    }
}