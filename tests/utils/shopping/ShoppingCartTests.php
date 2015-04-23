<?php

use maldoinc\utils\shopping\ShoppingCart;

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