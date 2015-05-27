<?php

use maldoinc\utils\session\SessionManager;

class TestSessionManager extends PHPUnit_Framework_TestCase
{
    protected $baseKey = 'somekey';
    protected $mock = array();
    /** @var SessionManager */
    protected $mgr;

    protected function getMultiDimensionalArray()
    {
        return array(
            'role' => array(
                'id'   => 1,
                'name' => 'Developers'
            ),
            'name' => 'maldoinc'
        );
    }

    public function setUp()
    {
        $this->mgr = new SessionManager($this->mock, $this->baseKey);
        $this->mgr->set('hello', 'world');
    }

    public function testSet()
    {
        $this->mgr->set('key', 'value');
        $this->assertEquals('value', $this->mock[$this->baseKey]['key']);

        $this->mgr->set('app.user.name', 'aldo');
        $this->assertEquals('aldo', $this->mock[$this->baseKey]['app']['user']['name']);

        // test set multi dimensional array
        $this->mgr->set('user', $this->getMultiDimensionalArray());

        $this->assertEquals('maldoinc', $this->mock[$this->baseKey]['user']['name']);
    }

    /**
     * @depends testSet
     */
    public function testGet()
    {
        $this->assertEquals('world', $this->mgr->get('hello'));
        $this->assertEquals('default', $this->mgr->get('nope', 'default'));

        $this->mgr->set('app.user.name', 'aldo');
        $this->assertEquals('aldo', $this->mgr->get('app.user.name'));

        $this->mgr->set('user', $this->getMultiDimensionalArray());
        $this->assertEquals('maldoinc', $this->mgr->get('user.name'));
        $this->assertEquals('Developers', $this->mgr->get('user.role.name'));
        $this->assertEquals(1, $this->mgr->get('user.role.id'));
    }

    public function testForget()
    {
        $this->mgr->forget('hello');
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

        $this->mgr->set('user.name', 'username');
        $this->assertEquals(true, $this->mgr->has('user'));
        $this->assertEquals(true, $this->mgr->has('user.name'));
        $this->assertEquals(false, $this->mgr->has('user.lastName'));
    }

    public function testPull()
    {
        $this->assertEquals('world', $this->mgr->pull('hello'));
        $this->assertEquals(null, $this->mgr->get('hello'));

        $this->mgr->set('user.name', 'maldoinc');
        $user = $this->mgr->pull('user');

        $this->assertEquals(false, $this->mgr->has('user'));
        $this->assertEquals(['name' => 'maldoinc'], $user);
    }
}