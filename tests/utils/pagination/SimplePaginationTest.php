<?php

use maldoinc\utils\pagination\SimplePagination;

class SimplePaginationTest extends PHPUnit_Framework_TestCase
{
    public function testSimplePagination()
    {
        SimplePagination::setDefaultRowsPerPage(2);

        $p = new SimplePagination(4, 1, '/test/p/%d');
        $expected = "<li><a href='/test/p/1'>1</a></li><li><a href='/test/p/2'>2</a></li>";

        $this->assertEquals($expected, $p->__toString());
        $this->assertEquals('', new SimplePagination(2, 1, '/whatever/%d'));
    }
}