<?php

namespace maldoinc\utils\shopping;

use maldoinc\utils\shopping\exceptions\ItemNotFoundException;

class Cart implements \Countable
{
    /** @var CartItem[] */
    protected $items = array();

    /**
     * Clears the shopping cart
     */
    public function clear()
    {
        $this->items = array();
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
     * Returns the item based on it's rowid
     *
     * @param $rowid
     * @return CartItem
     * @throws ItemNotFoundException
     */
    public function get($rowid)
    {
        $this->checkRowid($rowid);

        return $this->items[$rowid];
    }

    /**
     * @param $rowid
     * @throws ItemNotFoundException
     */
    protected function checkRowid($rowid)
    {
        if (!$this->has($rowid)) {
            throw new ItemNotFoundException(sprintf("Item with rowid '%s' not found", $rowid));
        }
    }

    /**
     * Determines whether the cart has or not the item with specified rowid
     *
     * @param $rowid
     * @return bool
     */
    public function has($rowid)
    {
        return array_key_exists($rowid, $this->items) && $this->items[$rowid] instanceof CartItem;
    }

    /**
     * Returns a copy of the shopping cart items
     *
     * @return CartItem[]
     */
    public function getItems()
    {
        return array_values($this->items);
    }

    /**
     * @param CartItem[] $items
     */
    public function setItems($items)
    {
        foreach ($items as $item) {
            $this->items[$item->getRowId()] = $item;
        }
    }

    /**
     * Return all the items that match a condition
     *
     * @param callable $c
     * @return CartItem[]
     */
    public function filter($c)
    {
        return array_values(array_filter($this->items, $c));
    }

    /**
     * @return float
     */
    public function getTotal()
    {
        return array_reduce($this->items, function ($carry, $item) {
            /** @var $item CartItem */
            return $carry + $item->getPrice() * $item->getQuantity();
        });
    }

    public function add(CartItem $item)
    {
        $this->items[$item->getRowId()] = $item;

        return $item->getRowId();
    }
    
    /**
     * Removes the product with the specified identifier from the shopping cart
     *
     * @param $rowid
     * @throws ItemNotFoundException
     */
    public function remove($rowid)
    {
        $this->checkRowid($rowid);

        unset($this->items[$rowid]);
    }
}