<?php

use maldoinc\utils\shopping\persistence\CartPersistentInterface;

class NullPersistenceStrategy implements CartPersistentInterface
{
    function save($data)
    {
    }

    function load()
    {
    }

    function clear()
    {
    }
}