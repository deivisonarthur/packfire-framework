<?php

namespace Packfire\Event;

/**
 * Test class for EventHandler.
 * Generated by PHPUnit on 2012-07-06 at 04:58:15.
 */
class EventHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Packfire\Event\EventHandler
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     * @covers \Packfire\Event\EventHandler::__construct
     */
    protected function setUp()
    {
        $this->object = new EventHandler($this);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers \Packfire\Event\EventHandler
     */
    public function testCombined()
    {
        $this->object->on(
            'click',
            function ($obj, $arg = null) {
                $obj->assertNull($arg);
                $obj->assertInstanceOf('\PHPUnit_Framework_TestCase', $obj);
            }
        );
        $this->object->trigger('click');
    }

    /**
     * @covers \Packfire\Event\EventHandler
     */
    public function testCombined2()
    {
        $this->object->on(
            'click',
            function ($obj, $arg = null) {
                $obj->assertEquals(5, $arg);
                $obj->assertInstanceOf('\PHPUnit_Framework_TestCase', $obj);
            }
        );
        $this->object->trigger('click', 5);
    }
}
