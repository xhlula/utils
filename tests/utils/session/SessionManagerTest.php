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

    public function testGetObjectMethod()
    {
        $cls = new stdClass();
        $cls->this = 'that';
        $cls->property = new stdClass();
        $cls->property->nested = true;

        $cls->property->fn = function () {
            return true;
        };

        $this->mgr->set('object', $cls);

        $this->assertEquals('that', $this->mgr->get('object.this'));
        $this->assertEquals(true, is_callable($this->mgr->get('object.property.fn')));
        $this->assertEquals(true, $this->mgr->get('object.property.nested'));
    }

    public function testNonExistentKey()
    {
        $this->assertEquals(null, $this->mgr->get('object.x.y.z'));
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

        $this->mgr->set('cls', new stdClass());
        $this->mgr->set('cls.newprop', function(){
            return true;
        });
        $this->assertEquals(true, is_callable($this->mgr->get('cls.newprop')));
    }

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

    public function testRemove()
    {
        $this->mgr->remove('hello');
        $this->assertEquals(null, $this->mgr->get('hello'));

        $this->mgr->set('user', array('name' => 'maldoinc', 'roleId' => 1, 'roleName' => 'Developers'));

        $this->mgr->remove('user.name');
        $this->assertEquals(array('roleName' => 'Developers', 'roleId' => 1), $this->mgr->get('user'));

        $cls = new stdClass();
        $cls->prop = true;
        $this->mgr->set('cls', $cls);
        $this->mgr->remove('cls.prop');

        $this->assertEquals(false, $this->mgr->has('cls.prop'));
    }


    public function testAll()
    {
        $this->mgr->set('key', 'value');
        $all = $this->mgr->all();

        $this->assertEquals(2, count($all));
        $this->assertEquals('value', $all['key']);
        $this->assertEquals('world', $all['hello']);
    }

    public function testClear()
    {
        // set data in other keys of the mock variable.
        // our flush method implementation should not modify them
        $this->mock['dummy'] = 'dummy';

        $this->mgr->set('key', 'value');
        $this->mgr->clear();

        $this->assertEquals(0, count($this->mgr->all()));
        $this->assertEquals('dummy', $this->mock['dummy']);
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
        $this->assertEquals(array('name' => 'maldoinc'), $user);
    }
}