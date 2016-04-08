<?php

use maldoinc\utils\session\SessionManager;
use maldoinc\utils\shopping\CartItem;
use maldoinc\utils\shopping\persistence\FilePersistenceStrategy;
use maldoinc\utils\shopping\persistence\SessionPersistenceStrategy;
use maldoinc\utils\shopping\PersistentCart;

class PersistentShoppingCartTests extends PHPUnit_Framework_TestCase
{
    protected $mock = array();

    public function sessionPersistenceDataProvider()
    {
        $key = 'shopping_cart_test';
        $f = __DIR__ . DIRECTORY_SEPARATOR . $key;
        $mgr = new SessionManager($this->mock, $key);

        return array(
            array(function () use ($f) {
                return new PersistentCart(new FilePersistenceStrategy($f));
            }),
            array(function () use ($mgr, $key) {
                return new PersistentCart(new SessionPersistenceStrategy($mgr, $key));
            })
        );
    }

    /**
     * @dataProvider sessionPersistenceDataProvider
     */
    public function testSessionPersistence($factory)
    {
        /** @var $a PersistentCart */
        $a = $factory();
        $rowid = $a->add(new CartItem('A', 1, 2));

        /** @var $b PersistentCart */
        $b = $factory();

        $this->assertEquals($a->count(), $b->count());
        $this->assertEquals($a->getTotal(), $b->getTotal());

        $item = $b->get($rowid);
        $this->assertEquals($rowid, $item->getRowId());

        $item->setQuantity(10);
        $b->save();
        $this->assertEquals(10, $b->getTotal());
        $b->remove($rowid);
        $this->assertEquals(0, $b->count());

        $a->clear();
    }
}