<?php

namespace maldoinc\utils\shopping;

/**
 * Class ShoppingCartItem
 * @package maldoinc
 */
class CartItem
{
    /** @var string */
    protected $rowId;

    /** @var string */
    protected $identifier;

    /** @var double */
    protected $quantity;

    /** @var double */
    protected $price;

    /** @var array */
    protected $data;

    /** @var double */
    protected $vatPercent = 0;

    /** @var bool */
    protected $vatIncluded = true;

    public function __construct($identifier, $price, $quantity = 1, $data = array())
    {
        $this->rowId = uniqid($identifier, false);
        $this->identifier = $identifier;
        $this->price = $price;
        $this->setQuantity($quantity);
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getRowId()
    {
        return $this->rowId;
    }

    /**
     * @param string $rowId
     */
    public function setRowId($rowId)
    {
        $this->rowId = $rowId;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return float
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param float $quantity
     * @throws exceptions\InvalidQuantityException
     */
    public function setQuantity($quantity)
    {
        if ($quantity < 0) {
            throw new exceptions\InvalidQuantityException(sprintf("Invalid quantity: %.2f", $quantity));
        }

        $this->quantity = $quantity;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return float
     */
    public function getVatPercent()
    {
        return $this->vatPercent;
    }

    /**
     * @param float $vatPercent
     * @return CartItem
     */
    public function setVatPercent($vatPercent)
    {
        $this->vatPercent = $vatPercent;
        return $this;
    }

    /**
     * @param boolean $vatIncluded
     * @return CartItem
     */
    public function setVatIncluded($vatIncluded)
    {
        $this->vatIncluded = $vatIncluded;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isVatIncluded()
    {
        return $this->vatIncluded;
    }

    public function getPriceInfo()
    {
        return new PriceCalculator($this->price, $this->quantity, $this->vatPercent, $this->vatIncluded);
    }
}