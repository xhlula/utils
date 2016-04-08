<?php

use maldoinc\utils\shopping\PriceCalculator;

class PriceCalculatorTests extends PHPUnit_Framework_TestCase
{
    public function testVatIncludedShouldPass()
    {
        $calc = new PriceCalculator(10, 1, 20, true);

        $this->assertEquals(8.333, $calc->getBasePrice(), '', 0.001);
        $this->assertEquals(8.333, $calc->getBasePricePerUnit(), '', 0.001);
        $this->assertEquals(1.667, $calc->getVat(), '', 0.001);
        $this->assertEquals(10, $calc->getTotalPrice());
    }

    public function testVatIncludedBigQuantityShouldPass()
    {
        $calc = new PriceCalculator(10, 60, 20, true);

        $this->assertEquals(500, $calc->getBasePrice(), '', 0.001);
        $this->assertEquals(8.333, $calc->getBasePricePerUnit(), '', 0.001);
        $this->assertEquals(100, $calc->getVat(), '', 0.001);
        $this->assertEquals(600, $calc->getTotalPrice());
    }

    public function testVatNotIncludedShouldPass()
    {
        $calc = new PriceCalculator(10, 1, 20, false);

        $this->assertEquals(10, $calc->getBasePrice());
        $this->assertEquals(10, $calc->getBasePricePerUnit());
        $this->assertEquals(2, $calc->getVat(), '', 0.001);
        $this->assertEquals(12, $calc->getTotalPrice());
    }

    public function testVatNotIncludedBigQuantityShouldPass()
    {
        $calc = new PriceCalculator(10, 60, 20, false);

        $this->assertEquals(600, $calc->getBasePrice(), '', 0.001);
        $this->assertEquals(10, $calc->getBasePricePerUnit());
        $this->assertEquals(120, $calc->getVat(), '', 0.001);
        $this->assertEquals(720, $calc->getTotalPrice());
    }
}