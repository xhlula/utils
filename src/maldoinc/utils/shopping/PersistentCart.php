<?php

namespace maldoinc\utils\shopping;

use maldoinc\utils\shopping\persistence\CartPersistentInterface;
use maldoinc\utils\shopping\persistence\NullPersistenceStrategy;

class PersistentCart extends Cart
{
    /** @var CartPersistentInterface */
    protected $intf = null;


    /**
     * @param CartPersistentInterface $intf
     */
    public function __construct(CartPersistentInterface $intf = null)
    {
        if ($intf === null) {
            $this->intf = new NullPersistenceStrategy();
        } else {
            $this->intf = $intf;
        }

        $this->load();
    }

    public function clear()
    {
        parent::clear();
        $this->intf->clear();
    }

    public function add($identifier, $data, $price, $qty = 1.0)
    {
        $rowid = parent::add($identifier, $data, $price, $qty);
        $this->save();

        return $rowid;
    }

    public function remove($rowid)
    {
        parent::remove($rowid);
        $this->save();
    }

    public function update($rowid, $qty, $data = null)
    {
        parent::update($rowid, $qty, $data);
        $this->save();
    }

    /**
     * Save the shopping cart data
     */
    protected function save()
    {
        $this->intf->save(serialize($this->items));
    }

    /**
     * Load shopping cart data.
     *
     * Overwrites any existing items the cart might have
     */
    protected function load()
    {
        $data = $this->intf->load();

        if ($data !== null) {
            $this->items = unserialize($data);
        }
    }
}