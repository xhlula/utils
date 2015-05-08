<?php

namespace maldoinc\utils\shopping;

use maldoinc\utils\shopping\persistence\CartPersistentInterface;

class PersistentCart extends Cart
{
    /** @var CartPersistentInterface */
    protected $intf = null;

    /**
     * @param CartPersistentInterface $intf
     */
    public function __construct(CartPersistentInterface $intf = null)
    {
        $this->intf = $intf;
        $this->load();
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
            $items = unserialize($data);

            if (is_array($items)) {
                $this->setItems($items);
            }
        }
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

    /**
     * Save the shopping cart data
     */
    protected function save()
    {
        $this->intf->save(serialize($this->getItems()));
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
}