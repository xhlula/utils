<?php

namespace maldoinc\utils\shopping\persistence;

/**
 * Allows persisting shopping the ShoppingCart class.
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