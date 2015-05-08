<?php

use maldoinc\utils\shopping\persistence\FilePersistenceStrategy;
use maldoinc\utils\shopping\PersistentCart;

class PersistentShoppingCartTests extends PHPUnit_Framework_TestCase
{
    public function testSessionPersistence()
    {
        $a = $this->getCart();
        $rowid = $a->add('A', array(), 1, 2);

        $b = $this->getCart();

        $this->assertEquals($a->count(), $b->count());
        $this->assertEquals($a->getTotal(), $b->getTotal());

        $item = $b->get($rowid);
        $this->assertEquals($rowid, $item->rowId);

        $b->update($rowid, 10);
        $this->assertEquals(10, $b->getTotal());
        $b->remove($rowid);
        $this->assertEquals(0, $b->count());

        $a->clear();
    }

    protected function getCart()
    {
        $f = __DIR__ . DIRECTORY_SEPARATOR . 'shopping_cart_test';

        return new PersistentCart(new FilePersistenceStrategy($f));
    }
}