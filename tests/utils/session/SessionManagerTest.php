<?php

use maldoinc\utils\session\SessionManager;

class TestSessionManager extends PHPUnit_Framework_TestCase
{
    protected $baseKey = 'somekey';
    protected $mock = array();
    /** @var SessionManager */
    protected $mgr;

    public function setUp()
    {
        $this->mgr = new SessionManager($this->mock, $this->baseKey);
        $this->mgr->set('hello', 'world');
    }

    public function testSet()
    {
        $this->mgr->set('key', 'value');
        $this->assertEquals('value', $this->mock[$this->baseKey]['key']);
    }

    /**
     * @depends testSet
     */
    public function testGet()
    {
        $this->assertEquals('world', $this->mgr->get('hello'));
        $this->assertEquals('default', $this->mgr->get('nope', 'default'));
    }

    public function testForget()
    {
        $this->mgr->forget('hello');
        $this->assertEquals(null, $this->mgr->get('hello'));
    }

    public function testPull()
    {
        $this->assertEquals('world', $this->mgr->pull('hello'));
        $this->assertEquals(null, $this->mgr->get('hello'));
    }

    public function testAll()
    {
        $this->mgr->set('key', 'value');
        $all = $this->mgr->all();

        $this->assertEquals(2, count($all));
        $this->assertEquals('value', $all['key']);
        $this->assertEquals('world', $all['hello']);
    }

    /**
     * @depends testAll
     */
    public function testFlush()
    {
        $this->mgr->set('key', 'value');
        $this->mgr->flush();

        $this->assertEquals(0, count($this->mgr->all()));
    }

    public function testHas()
    {
        $this->mgr->set('key', 'value');

        $this->assertEquals(true, $this->mgr->has('hello'));
        $this->assertEquals(true, $this->mgr->has('key'));
        $this->assertEquals(false, $this->mgr->has('nope'));
    }
}