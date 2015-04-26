<?php

namespace maldoinc\utils\shopping;

class Cart implements \Countable
{
    /* @var $items CartItem[] */
    protected $items = array();

    public function clear()
    {
        $this->items = array();
    }

    /**
     * @return CartItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @param $index
     * @throws InvalidIndexException
     * @return CartItem
     */
    public function &getItemAt($index)
    {
        if (array_key_exists($index, $this->items)) {
            return $this->items[$index];
        } else {
            throw new InvalidIndexException("Item cannot be found");
        }
    }

    /**
     * Returns the item based on it's identifier
     *
     * @param $identifier
     * @return CartItem
     */
    public function &get($identifier)
    {
        return $this->getItemAt($this->indexOf($identifier));
    }

    /**
     * Returns the index of the element with the specified identifier
     *
     * @param $identifier
     * @return int
     */
    public function indexOf($identifier)
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

    function getTotal()
    {
        return array_reduce($this->items, function ($carry, $item) {
            /** @var $item CartItem */
            return $carry + $item->price_unit * $item->quantity;
        });
    }

    /**
     * Adds/updates a product in the shopping cart
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
     * Removes the item at the specified index
     *
     * @param $index
     */
    public function removeItemAt($index)
    {
        array_splice($this->items, $index, 1);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->items);
    }
}