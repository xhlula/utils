<?php

use maldoinc\utils\shopping\exceptions\InvalidPropertyException;
use maldoinc\utils\shopping\exceptions\InvalidQuantityException;
use maldoinc\utils\shopping\Cart;
use maldoinc\utils\shopping\CartItem;
use maldoinc\utils\shopping\exceptions\ItemNotFoundException;

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
        $this->cart->add('AB12', array(), 100);
        $this->cart->add('AB123', array(), 100);
        $cart->clear();

        $this->assertEquals(0, count($cart));
    }

    public function testTotal()
    {
        $this->cart->clear();

        $this->cart->add('AB12', array(), 100);
        $this->assertEquals($this->cart->getTotal(), 100);

        $this->cart->add('AB12', array(), 1000, 2);
        $this->assertEquals($this->cart->getTotal(), 2100);
    }

    public function testIsEmpty()
    {
        $this->cart->clear();
        $this->assertEquals(true, $this->cart->isEmpty());
    }

    public function testInvalidProperty()
    {
        $this->cart->clear();
        $id = $this->cart->add('X', array(), 1, 1);

        $item = $this->cart->get($id);

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

        $this->assertEquals(true, reset($items) instanceof CartItem);
    }

    public function testGet()
    {
        $this->cart->clear();

        $a = $this->cart->add('A', array(), 3.14, 1);
        $this->cart->add('B', array(), 4.14, 1);

        $item = $this->cart->get($a);
        $this->assertEquals(3.14, $item->price, '', 0.001);

        try {
            // attempt to access an unknown item
            $this->cart->get('oops!');

            $this->fail('Should throw exception');
        } catch(ItemNotFoundException $e) {
            $this->assertEquals(true, true);
        }
    }

    public function testAddInvalidQty()
    {
        try {
            $this->cart->add('X', array(), 1, -1);
            $this->fail('Should throw exception');
        } catch(InvalidQuantityException $e) {
            $this->assertEquals(true, true);
        }
    }

    public function testUpdate()
    {
        $this->cart->clear();
        $a = $this->cart->add('A', array(), 1, 1);
        $this->cart->add('B', array(), 3, 1);

        $this->cart->update($a, 3, array('x' => 'y'));

        $this->assertEquals(6, $this->cart->getTotal());

        $item = $this->cart->get($a);
        $this->assertEquals('y', $item->data['x']);

        $this->cart->update($a, -1);
        $this->assertEquals(1, $this->cart->count());

        try {
            $this->cart->update('OOPS', 1234);
            $this->fail('Should throw exception');
        } catch(ItemNotFoundException $e) {
            $this->assertEquals(true, true);
        }
    }

    public function testQuantity()
    {
        $this->cart->clear();

        $a = $this->cart->add('A', array(), 3.14, 1);

        /** @var CartItem $item */
        $item = $this->cart->get($a);
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
        $first = $this->cart->add('XXYZ', array(), 100, 1.5);
        $this->cart->add('ITEMCODE', array(), 100, 1.5);

        $this->cart->remove($first);
        $this->assertEquals($this->cart->count(), 2);
    }
}