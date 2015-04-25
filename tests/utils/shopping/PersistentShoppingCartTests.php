<?php

use maldoinc\utils\shopping\FilePersistenceStrategy;
use maldoinc\utils\shopping\PersistentShoppingCart;

class PersistentShoppingCartTests extends PHPUnit_Framework_TestCase
{
    public function testSessionPersistence()
    {
        $factory = function ()  {
            $f = __DIR__ . DIRECTORY_SEPARATOR . 'shopping_cart_test';

            return new PersistentShoppingCart(new FilePersistenceStrategy($f));
        };

        try {
            /** @var PersistentShoppingCart $a */
            $a = $factory();
            $a->add('A', [], 1, 2);
            $a->save();

            /** @var PersistentShoppingCart $b */
            $b = $factory();
            $this->assertEquals($a->getCount(), $b->getCount());
            $this->assertEquals($a->getTotal(), $b->getTotal());
        } finally {
            $a->clear();
        }
    }
}