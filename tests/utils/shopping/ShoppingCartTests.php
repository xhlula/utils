<?php

use maldoinc\utils\shopping\InvalidPropertyException;
use maldoinc\utils\shopping\InvalidQuantityException;
use maldoinc\utils\shopping\ShoppingCart;
use maldoinc\utils\shopping\ShoppingCartItem;

class ShoppingCartTests extends PHPUnit_Framework_TestCase
{
    /** @var ShoppingCart */
    protected $cart;

    protected function setUp()
    {
        $this->cart = new ShoppingCart();
    }

    public function testClear()
    {
        $cart = new ShoppingCart();
        $cart->clear();

        $this->assertEquals(0, $cart->getCount());
    }

    public function testTotal()
    {
        $this->cart->clear();

        $this->cart->add('AB12', array(), 100);
        $this->assertEquals($this->cart->getTotal(), 100);

        $this->cart->add('AB12', array(), 999, 2);
        $this->assertEquals($this->cart->getTotal(), 300);
    }

    public function testIndexOf()
    {
        $this->cart->clear();

        $this->cart->add(101, array(), 100, 1.5);
        $this->cart->add('XXYZ', array(), 100, 1.5);
        $this->cart->add('ITEMCODE', array(), 100, 1.5);

        $this->assertEquals($this->cart->indexOf('XXYZ'), 1);
        $this->assertEquals($this->cart->indexOf('ITEMCODE'), 2);
        $this->assertEquals($this->cart->indexOf(101), 0);
    }

    public function testInvalidProperty()
    {
        $this->cart->clear();
        $this->cart->add('X', [], 1, 1);

        $item = $this->cart->getItemAt(0);
        try {
            $item->x = 'y';

            $this->fail('Invalid property. Should fail');
        } catch (InvalidPropertyException $e) {
            $this->assertEquals(true, true);
        }
    }

    public function testQuantity()
    {
        $this->cart->clear();

        $this->cart->add('A', array(), 3.14, 1);

        /** @var ShoppingCartItem $item */
        $item = $this->cart->getItemAt(0);
        try {
            $item->quantity = -1;

            $this->fail('Quantity should not be negative');
        } catch (InvalidQuantityException $e) {
            // should probably have a pass method
            $this->assertEquals(true, true);
        }
    }

    public function testRemove()
    {
        $this->cart->clear();

        $this->cart->add(101, array(), 100, 1.5);
        $this->cart->add('XXYZ', array(), 100, 1.5);
        $this->cart->add('ITEMCODE', array(), 100, 1.5);

        $this->cart->remove('XXYZ');
        $this->assertEquals($this->cart->getCount(), 2);
        $this->assertEquals($this->cart->indexOf('ITEMCODE'), 1);

    }
}