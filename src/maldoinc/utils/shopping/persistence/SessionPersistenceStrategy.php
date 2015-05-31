<?php

namespace maldoinc\utils\shopping\persistence;

use maldoinc\utils\session\SessionManagerInterface;

class SessionPersistenceStrategy implements CartPersistentInterface
{
    protected $key;
    protected $sess;

    public function __construct(SessionManagerInterface $sess, $key)
    {
        $this->sess = $sess;
        $this->key = $key;
    }

    /**
     * @return void
     */
    public function clear()
    {
        $this->sess->remove($this->key);
    }

    public function save($data)
    {
        $this->sess->set($this->key, $data);
    }

    public function load()
    {
        return $this->sess->get($this->key);
    }
}