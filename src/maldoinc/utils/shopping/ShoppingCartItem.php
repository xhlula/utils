<?php

namespace maldoinc\utils\shopping;

/**
 * Class ShoppingCartItem
 * @package maldoinc
 *
 * @property string identifier
 * @property float quantity
 * @property float price_unit
 * @property array data
 */
class ShoppingCartItem implements \Serializable
{
    public $attr = array();

    public function __construct($identifier, $quantity, $price_unit, $data)
    {
        $this->attr['identifier'] = $identifier;
        $this->attr['quantity'] = $quantity;
        $this->attr['price_unit'] = $price_unit;
        $this->attr['data'] = $data;
    }

    protected function hasProp($name)
    {
        return array_key_exists($name, $this->attr);
    }

    protected function checkProp($name)
    {
        if (!$this->hasProp($name)) {
            throw new InvalidPropertyException(sprintf("Invalid property: %s on class %s", $name, __CLASS__));
        }
    }

    public function __get($name)
    {
        $this->checkProp($name);

        return $this->attr[$name];
    }

    public function __set($name, $val)
    {
        $this->checkProp($name);

        if ($name === 'quantity' && (float)$val < 0) {
            throw new InvalidQuantityException("Quantity cannot be negative");
        }

        $this->attr[$name] = $val;
    }

    public function __isset($name)
    {
        return isset($this->attr[$name]);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        return serialize($this->attr);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     */
    public function unserialize($serialized)
    {
        $this->attr = unserialize($serialized);
    }
}