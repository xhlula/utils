<?php

use maldoinc\utils\pagination\Pagination;

class PaginationTest extends PHPUnit_Framework_TestCase
{
    public function testPageCount()
    {
        $p = 9;

        $pagination = new Pagination(100, 5, 1, $p);
        $this->assertEquals(count($pagination->getPages()), $p);

        $pagination = new Pagination(0, 5, 1, 9);
        $this->assertEquals(count($pagination->getPages()), 0);

    }

    public function testPageValues()
    {
        // test the pages to be rendered. 9 items visible at the time.
        // pagination should start at 5 and end at 13
        $pagination = new Pagination(100, 5, 9, 9);

        $this->assertEquals($pagination->getPages(), array(5, 6, 7, 8, 9, 10, 11, 12, 13));
    }

    public function testSmallData()
    {
        $pagination = new Pagination(1, 5, 1, 9);

        $this->assertEquals($pagination->getPages(), array(1));
    }

    public function testGetLimitOffset()
    {
        $pagination = new Pagination(100, $per_page = 5, 1, 9);

        $this->assertEquals($pagination->getLimitOffset(), 0);

        $pagination->setCurrentPage(5);
        $this->assertEquals($pagination->getLimitOffset(), 4 * $per_page);
    }

    public function testGetHTML()
    {
        $cb = function ($page, $label) {
            return $label;
        };

        $pagination = new Pagination(200, 10, 1, 9);
        $pagination->showFirstLast = false;

        $this->assertEquals('123456789', $pagination->getHtml($cb));


        $pagination = new Pagination(200, 10, 1, 9);
        $pagination->setCurrentPage(10);
        $pagination->showFirstLast = true;
        $this->assertEquals('First67891011121314Last', $pagination->getHtml($cb));

        $pagination = new Pagination(0, 10, 1, 9);
        $this->assertEquals('', $pagination->getHtml($cb));
    }

    public function testSetPage()
    {
        $pagination = new Pagination(1, 5, 1, 9);

        $pagination->setCurrentPage(-1);
        $this->assertEquals($pagination->getCurrentPage(), 1);

        $pagination->setCurrentPage("hello world");
        $this->assertEquals($pagination->getCurrentPage(), 1);
    }

    public function testEndPages()
    {
        $pagination = new Pagination(100, 5, 19, 9);

        $this->assertEquals($pagination->getPages(), array(12, 13, 14, 15, 16, 17, 18, 19, 20));
    }
}
