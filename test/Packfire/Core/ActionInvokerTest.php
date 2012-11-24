<?php
namespace Packfire\Core;

/**
 * Test class for ActionInvoker.
 * Generated by PHPUnit on 2012-09-28 at 02:25:22.
 */
class ActionInvokerTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var ActionInvoker
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {

    }

    public function action($name, $age){
        return $name . $age;
    }

    public static function staticAction($height, $weight){
        return floor($weight / pow($height, 2));
    }

    /**
     * @covers ActionInvoker::invoke
     */
    public function testInvoke() {
        $object = new ActionInvoker(array($this, 'action'));
        $this->assertEquals('John Smith5', $object->invoke(array('age' => 5, 'name' => 'John Smith')));
    }

    /**
     * @covers ActionInvoker::invoke
     */
    public function testInvoke2() {
        $params = array();
        $object = new ActionInvoker(function($name, $age) use(&$params){
            $params = func_get_args();
            return true;
        });
        $this->assertTrue($object->invoke(array('age' => 5, 'name' => 'John Smith')));
        $this->assertEquals(array('John Smith', 5), $params);
    }

    /**
     * @covers ActionInvoker::invoke
     */
    public function testInvoke3() {
        $object = new ActionInvoker('strpos');
        $this->assertEquals(6, $object->invoke(array('needle' => 'World', 'haystack' => 'Hello World!')));
    }

    /**
     * @covers pActionInvoker::invoke
     */
    public function testInvoke4() {
        $object = new ActionInvoker('Packfire\Core\ActionInvokerTest::staticAction');
        $this->assertEquals(28, $object->invoke(array('weight' => 84, 'height' => '1.72')));
    }

}