<?php

namespace maldoinc\utils\shopping;

/**
 * Allows persistence of shopping cart via the interface passed to the constructor
 *
 * Class PersistentShoppingCart
 * @package maldoinc\utils\shopping
 */
class PersistentShoppingCart extends ShoppingCart
{
    /** @var ShoppingCartPersistentInterface */
    protected $intf;

    public function __construct(ShoppingCartPersistentInterface $intf)
    {
        $this->intf = $intf;
        $this->load();
    }

    public function save()
    {
        $this->intf->save(serialize($this->items));
    }

    public function load()
    {
        $items = $this->intf->load();

        if ($items !== null) {
            $this->setItems(unserialize($items));
        }
    }

    public function clear()
    {
        parent::clear();
        $this->intf->clear();
    }
}