<?php

use maldoinc\utils\shopping\CartItem;

class CartItemTests extends PHPUnit_Framework_TestCase
{
    public function testCartItemPriceDetails()
    {
        $item = new CartItem('A001', 10, 1);
        $item->setVatPercent(20);

        $this->assertEquals(8.333, $item->getPriceInfo()->getBasePrice(), '', 0.001);
        $this->assertEquals(1.667, $item->getPriceInfo()->getVat(), '', 0.001);
        $this->assertEquals(10, $item->getPriceInfo()->getTotalPrice());
    }

    public function testGettersSetters()
    {
        $item = new CartItem('SKUID', 133);

        $item->setRowId('SKU123ROWID');
        $item->setVatPercent(10);
        $item->setQuantity(14);
        $item->setData(array('size' => 'L'));
        $item->setIdentifier('NEWSKU');
        $item->setVatIncluded(false);


        $data = $item->getData();

        $this->assertEquals('NEWSKU', $item->getIdentifier());
        $this->assertEquals(133, $item->getPrice());
        $this->assertEquals(14, $item->getQuantity());
        $this->assertEquals('L', $data['size']);
        $this->assertEquals('SKU123ROWID', $item->getRowId());
        $this->assertEquals(10, $item->getVatPercent());

        $item->setPrice(20);
        $this->assertEquals(20, $item->getPrice());

        $this->assertEquals(false, $item->isVatIncluded());
    }
}