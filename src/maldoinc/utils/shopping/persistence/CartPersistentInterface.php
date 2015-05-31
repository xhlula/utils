<?php

namespace maldoinc\utils\shopping\persistence;

/**
 * Allows persisting shopping the ShoppingCart class.
 *
 * NB: this is not handling a collection class. All it asks to do is to store and retrieve a string which
 * represents the cart data. Serialization itself is handled by php's built-in mechanisms and is not up to
 * the interface to decide how to do it.
 *
 * Interface ShoppingCartPersistentInterface
 * @package maldoinc\utils\shopping
 */
interface CartPersistentInterface
{
    /**
     *
     * @param string $data
     * @return void
     */
    function save($data);

    /**
     * @return string
     */
    function load();

    /**
     * Destroy all data
     *
     * @return void
     */
    function clear();
}