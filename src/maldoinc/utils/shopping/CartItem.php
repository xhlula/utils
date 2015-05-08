<?php

namespace maldoinc\utils\shopping;

/**
 * Class ShoppingCartItem
 * @package maldoinc
 *
 * @property string rowId
 * @property string identifier
 * @property float quantity
 * @property float price
 * @property array data
 */
class CartItem
{
    public $attr = array(
        'rowId'      => null,
        'identifier' => null,
        'quantity'   => null,
        'price'      => null,
        'data'       => null
    );

    public function __construct($rowid, $identifier, $quantity, $price, $data)
    {
        $this->rowId = $rowid;
        $this->identifier = $identifier;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->data = $data;
    }

    public function __get($name)
    {
        $this->checkProp($name);

        return $this->attr[$name];
    }

    public function __set($name, $val)
    {
        $this->checkProp($name);

        if ($name === 'quantity' && (float)$val <= 0) {
            throw new exceptions\InvalidQuantityException(sprintf("Invalid quantity: %.2f", $val));
        }

        $this->attr[$name] = $val;
    }

    protected function checkProp($name)
    {
        if (!$this->hasProp($name)) {
            throw new exceptions\InvalidPropertyException(sprintf("Invalid property: %s on class %s", $name, __CLASS__));
        }
    }

    protected function hasProp($name)
    {
        return array_key_exists($name, $this->attr);
    }

    public function __isset($name)
    {
        return isset($this->attr[$name]);
    }
}