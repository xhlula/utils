<?php

namespace maldoinc\utils\shopping\persistence;

use maldoinc\utils\session\SessionManagerInterface;

class SessionPersistenceStrategy implements CartPersistentInterface
{
    protected $key = 'shopping_cart_data';
    protected $sess;

    public function __construct(SessionManagerInterface $sess)
    {
        $this->sess = $sess;
    }

    /**
     * @return void
     */
    public function clear()
    {
        $this->sess->flush();
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