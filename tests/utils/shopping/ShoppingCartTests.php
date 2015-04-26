<?php

use maldoinc\utils\shopping\InvalidIndexException;
use maldoinc\utils\shopping\InvalidPropertyException;
use maldoinc\utils\shopping\InvalidQuantityException;
use maldoinc\utils\shopping\Cart;
use maldoinc\utils\shopping\CartItem;

class ShoppingCartTests extends PHPUnit_Framework_TestCase
{
    /** @var Cart */
    protected $cart;

    protected function setUp()
    {
        $this->cart = new Cart();
    }

    public function testClear()
    {
        $cart = new Cart();
        $cart->clear();

        $this->assertEquals(0, count($cart));
    }

    public function testTotal()
    {
        $this->cart->clear();

        $this->cart->add('AB12', array(), 100);
        $this->assertEquals($this->cart->getTotal(), 100);

        $this->cart->add('AB12', array(), 999, 2);
        $this->assertEquals($this->cart->getTotal(), 300);
    }

    public function testIsEmpty()
    {
        $this->cart->clear();
        $this->assertEquals(true, $this->cart->isEmpty());
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

        $this->assertEquals(false, isset($item->nonExistingProperty));

        try {
            $item->shouldThrowException = 'y';

            $this->fail('Invalid property. Should fail');
        } catch (InvalidPropertyException $e) {
            $this->assertEquals(true, true);
        }
    }

    public function testCountable()
    {
        $this->cart->clear();

        $this->assertEquals(0, count($this->cart));
    }

    public function testGetItems()
    {
        $this->cart->clear();

        $this->cart->add('A', array(), 3.14, 1);
        $items = $this->cart->getItems();
        $this->assertEquals('A', $items[0]->identifier);
    }

    public function testGet()
    {
        $this->cart->clear();

        $this->cart->add('A', array(), 3.14, 1);
        $this->cart->add('B', array(), 3.14, 1);

        $item = $this->cart->get('A');

        $this->assertEquals(3.14, $item->price_unit, '', 0.001);

        try {
            // attempt to access an unknown item
            $this->cart->get('C');

            $this->fail('Should throw exception');
        } catch(InvalidIndexException $e) {
            $this->assertEquals(true, true);
        }
    }

    public function testQuantity()
    {
        $this->cart->clear();

        $this->cart->add('A', array(), 3.14, 1);

        /** @var CartItem $item */
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
        $this->assertEquals($this->cart->count(), 2);
        $this->assertEquals($this->cart->indexOf('ITEMCODE'), 1);

    }
}