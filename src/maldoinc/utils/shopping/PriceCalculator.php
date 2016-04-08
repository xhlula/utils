<?php


namespace maldoinc\utils\shopping;


class PriceCalculator
{
    protected $basePricePerUnit;
    protected $basePrice;
    protected $vat;
    protected $totalPrice;
    protected $quantity;

    public function __construct($amount, $quantity, $vatPercent, $vatIncluded)
    {
        $this->quantity = $quantity;

        if ($vatIncluded) {
            $this->totalPrice = $amount * $quantity;
            $this->basePrice = $this->totalPrice / (1 + $vatPercent / 100);
            $this->vat = $this->totalPrice - $this->basePrice;

            $this->basePricePerUnit = $this->basePrice / $quantity;
        } else {
            $this->basePricePerUnit = $amount;

            $this->basePrice = $amount * $quantity;
            $this->vat = $this->basePrice * ($vatPercent / 100);
            $this->totalPrice = $this->basePrice + $this->vat;
        }
    }

    /**
     * @return double
     */
    public function getBasePricePerUnit()
    {
        return $this->basePricePerUnit;
    }

    /**
     * @return double
     */
    public function getBasePrice()
    {
        return $this->basePrice;
    }

    /**
     * @return mixed
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * @return double
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * @return double
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
}