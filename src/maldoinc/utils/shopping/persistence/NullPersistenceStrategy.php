<?php

namespace maldoinc\utils\shopping\persistence;

class NullPersistenceStrategy implements CartPersistentInterface
{
    function save($data)
    {
    }

    function load()
    {
        return null;
    }

    function clear()
    {
    }
}