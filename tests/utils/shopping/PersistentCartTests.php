<?php

use maldoinc\utils\shopping\FilePersistenceStrategy;
use maldoinc\utils\shopping\PersistentCart;

class PersistentShoppingCartTests extends PHPUnit_Framework_TestCase
{
    public function testSessionPersistence()
    {
        $factory = function ()  {
            $f = __DIR__ . DIRECTORY_SEPARATOR . 'shopping_cart_test';

            return new PersistentCart(new FilePersistenceStrategy($f));
        };

        try {
            /** @var PersistentCart $a */
            $a = $factory();
            $a->add('A', [], 1, 2);
            $a->save();

            /** @var PersistentCart $b */
            $b = $factory();
            $this->assertEquals($a->count(), $b->count());
            $this->assertEquals($a->getTotal(), $b->getTotal());
        } finally {
            $a->clear();
        }
    }
}