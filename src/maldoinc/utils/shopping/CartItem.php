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

    public function __construct($rowId, $identifier, $quantity, $price, $data)
    {
        $this->rowId = $rowId;
        $this->identifier = $identifier;
        $this->setQuantity($quantity);
        $this->price = $price;
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
}