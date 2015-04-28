<?php

use maldoinc\utils\shopping\persistence\FilePersistenceStrategy;
use maldoinc\utils\shopping\Cart;

class PersistentShoppingCartTests extends PHPUnit_Framework_TestCase
{
    public function testSessionPersistence()
    {
        $factory = function () {
            $f = __DIR__ . DIRECTORY_SEPARATOR . 'shopping_cart_test';

            return new Cart(new FilePersistenceStrategy($f));
        };


        /** @var Cart $a */
        $a = $factory();
        $a->add('A', [], 1, 2);

        /** @var Cart $b */
        $b = $factory();
        $this->assertEquals($a->count(), $b->count());
        $this->assertEquals($a->getTotal(), $b->getTotal());

        $a->clear();
    }
}