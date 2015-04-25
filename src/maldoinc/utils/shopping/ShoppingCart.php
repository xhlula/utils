<?php

namespace maldoinc\utils\shopping;

class ShoppingCart
{
    /* @var $items ShoppingCartItem[] */
    protected $items = array();

    public function getCount()
    {
        return count($this->items);
    }

    public function clear()
    {
        $this->items = array();
    }

    /**
     * @return ShoppingCartItem[]
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
     * @return ShoppingCartItem
     */
    public function &getItemAt($index)
    {
        return $this->items [$index];
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
            /** @var $item ShoppingCartItem */
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
            $this->items[] = new ShoppingCartItem($identifier, $qty, $price, $data);
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
}