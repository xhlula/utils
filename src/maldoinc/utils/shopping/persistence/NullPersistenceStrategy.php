<?php

namespace maldoinc\utils\shopping\persistence;

class NullPersistenceStrategy implements CartPersistentInterface
{
    public function save($data)
    {
    }

    public function load()
    {
        return null;
    }

    public function clear()
    {
    }
}