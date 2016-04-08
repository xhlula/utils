<?php

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

        $cart->add(new CartItem('AB123', 100));
        $cart->add(new CartItem('AB123', 100));

        $cart->clear();

        $this->assertCount(0, $cart);
    }

    public function testFilter()
    {
        $this->cart->clear();

        $this->cart->add(new CartItem('AB12', 100));
        $this->cart->add(new CartItem('ABC', 1001));

        $items = $this->cart->filter(function(CartItem $item) {
            return $item->getPrice() === 1001;
        });

        $this->assertCount(1, $items);
        $this->assertEquals(true, isset($items[0]));
    }

    public function testTotal()
    {
        $this->cart->clear();

        $this->cart->add(new CartItem('AB12', 100));
        $this->assertEquals($this->cart->getTotal(), 100);

        $this->cart->add(new CartItem('AB12', 1000, 2));
        $this->assertEquals($this->cart->getTotal(), 2100);
    }

    public function testIsEmpty()
    {
        $this->cart->clear();
        $this->assertEquals(true, $this->cart->isEmpty());
    }

    public function testCountable()
    {
        $this->cart->clear();

        $this->assertCount(0, $this->cart);
    }

    public function testGetItems()
    {
        $this->cart->clear();

        $this->cart->add(new CartItem('A', 3.14));
        $items = $this->cart->getItems();

        $this->assertEquals(true, reset($items) instanceof CartItem);
    }

    public function testGet()
    {
        $this->cart->clear();

        $a = $this->cart->add(new CartItem('A', 3.14));
        $this->cart->add(new CartItem('A', 4.14, 1));

        $item = $this->cart->get($a);
        $this->assertEquals(3.14, $item->getPrice(), '', 0.001);

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
            $this->cart->add(new CartItem('X', 1, -1));
            $this->fail('Should throw exception');
        } catch(InvalidQuantityException $e) {
            $this->assertEquals(true, true);
        }
    }

    public function testUpdate()
    {
        $this->cart->clear();

        $itemA = new CartItem('A', 1);
        $itemB = new CartItem('B', 3, 1);

        $a = $this->cart->add($itemA);
        $this->cart->add($itemB);

        $itemA->setQuantity(3);
        $itemA->setData(array('x' => 'y'));

        $this->assertEquals(6, $this->cart->getTotal());

        $data = $this->cart->get($a)->getData();
        $this->assertEquals('y', $data['x']);
    }

    public function testQuantity()
    {
        $this->cart->clear();

        $a = $this->cart->add(new CartItem('A', 3.14, 1));

        /** @var CartItem $item */
        $item = $this->cart->get($a);
        try {
            $item->setQuantity(-1);

            $this->fail('Quantity should not be negative');
        } catch (InvalidQuantityException $e) {
            // should probably have a pass method
            $this->assertEquals(true, true);
        }
    }

    public function testRemove()
    {
        $this->cart->clear();

        $this->cart->add(new CartItem(101, 100, 1.5));
        $first = $this->cart->add(new CartItem('XXYZ', 100, 1.5));
        $this->cart->add(new CartItem('ITEMCODE', 100, 1.5));

        $this->cart->remove($first);
        $this->assertEquals($this->cart->count(), 2);
    }
    
    public function testCartItemPriceDetails()
    {
        $item = new CartItem('A001', 10, 1);
        $item->setVatPercent(20);

        $this->assertEquals(8.333, $item->getPriceInfo()->getBasePrice(), '', 0.001);
        $this->assertEquals(1.667, $item->getPriceInfo()->getVat(), '', 0.001);
        $this->assertEquals(10, $item->getPriceInfo()->getTotalPrice());
        $this->assertEquals(1, $item->getPriceInfo()->getQuantity());
    }
}