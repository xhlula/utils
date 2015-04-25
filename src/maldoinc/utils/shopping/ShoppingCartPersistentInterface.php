<?php

namespace maldoinc\utils\shopping;

/**
 * Allows persisting shopping the ShoppingCart class.
 *
 * Interface ShoppingCartPersistentInterface
 * @package maldoinc\utils\shopping
 */
interface ShoppingCartPersistentInterface
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
     * @return void
     */
    function clear();
}