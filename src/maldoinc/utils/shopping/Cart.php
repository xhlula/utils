<?php

namespace maldoinc\utils\shopping;

class Cart implements \Countable
{
    /* @var $items CartItem[] */
    protected $items = array();

    /** @var CartPersistentInterface */
    protected $intf = null;

    /**
     * @param CartPersistentInterface $intf
     */
    public function __construct(CartPersistentInterface $intf = null)
    {
        $this->intf = $intf;

        if ($this->intf !== null) {
            $this->load();
        }
    }

    /**
     * Save the shopping cart data
     */
    public function save()
    {
        $this->intf->save(serialize($this->items));
    }

    /**
     * Load shopping cart data.
     *
     * Overwrites any existing items the cart might have
     */
    public function load()
    {
        $items = $this->intf->load();

        if ($items !== null) {
            $this->setItems(unserialize($items));
        }
    }

    /**
     * Clears the shopping cart
     */
    public function clear()
    {
        $this->items = array();

        if ($this->intf !== null) {
            $this->intf->clear();
        }
    }

    /**
     * Determines whether the shopping cart is empty or not
     *
     * @return bool
     */
    public function isEmpty()
    {
        return $this->count() === 0;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Counts the items of the shopping cart
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Returns the item based on it's identifier
     *
     * @param $identifier
     * @return CartItem
     */
    public function get($identifier)
    {
        return $this->getItemAt($this->indexOf($identifier));
    }

    /**
     * Returns a copy of the shopping cart items
     *
     * @return CartItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set the items of the shopping cart
     *
     * @param CartItem[] $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @return float
     */
    public function getTotal()
    {
        return array_reduce($this->items, function ($carry, $item) {
            /** @var $item CartItem */
            return $carry + $item->price * $item->quantity;
        });
    }

    /**
     * Adds or updates a product in the shopping cart
     *
     * @param mixed $identifier
     * @param array $data
     * @param float $price
     * @param float $qty
     */
    public function add($identifier, $data, $price, $qty = 1.0)
    {
        $index = $this->indexOf($identifier);

        // In case item is not found, add it. Else update quantity
        if ($index == -1) {
            $this->items[] = new CartItem($identifier, $qty, $price, $data);
        } else {
            $this->items[$index]->quantity += $qty;
        }
    }

    /**
     * Removes the product with the specified identifier from the shopping cart
     *
     * @param $identifier
     */
    public function remove($identifier)
    {
        $this->removeItemAt($this->indexOf($identifier));
    }

    /**
     * Updates an item
     *
     * @param $identifier
     * @param $qty
     * @param array $data
     * @throws ItemNotFoundException
     */
    public function update($identifier, $qty, $data = null)
    {
        $idx = $this->indexOf($identifier);

        if ($idx === -1) {
            throw new ItemNotFoundException(sprintf("Item with identifier '%s' not found", $identifier));
        }

        if ($qty <= 0) {
            $this->removeItemAt($idx);

            return;
        }

        $this->items[$idx]->quantity = $qty;
        if ($data !== null) {
            $this->items[$idx]->data = $data;
        }
    }

    /**
     * Returns the index of the element with the specified identifier
     *
     * @param $identifier
     * @return int
     */
    protected function indexOf($identifier)
    {
        $position = 0;
        foreach ($this->items as $item) {
            if ($item->identifier === $identifier) {
                return $position;
            }
            $position++;
        }

        return -1;
    }

    /**
     * Removes the item at the specified index
     *
     * @param $index
     */
    protected function removeItemAt($index)
    {
        array_splice($this->items, $index, 1);
    }


    /**
     * @param $index
     * @throws InvalidIndexException
     * @return CartItem
     */
    protected function getItemAt($index)
    {
        if (array_key_exists($index, $this->items)) {
            return $this->items[$index];
        } else {
            throw new InvalidIndexException("Item cannot be found");
        }
    }
}