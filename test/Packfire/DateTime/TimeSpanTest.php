<?php

namespace Packfire\DateTime;

/**
 * Test class for TimeSpan.
 * Generated by PHPUnit on 2012-04-28 at 02:31:47.
 */
class TimeSpanTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Packfire\DateTime\TimeSpan
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     * @covers \Packfire\DateTime\TimeSpan::__construct
     */
    protected function setUp()
    {
        $this->object = new TimeSpan(3695);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers \Packfire\DateTime\TimeSpan::hour
     */
    public function testHour()
    {
        $this->assertEquals(1, $this->object->hour());
        $this->object->hour(2);
        $this->assertEquals(2, $this->object->hour());
        $this->assertEquals(5, $this->object->hour(5));
    }

    /**
     * @covers \Packfire\DateTime\TimeSpan::day
     */
    public function testDay()
    {
        $this->assertEquals(0, $this->object->day());
        $this->object->day(5);
        $this->assertEquals(5, $this->object->day());
        $this->assertEquals(3, $this->object->day(3));
    }

    /**
     * @covers \Packfire\DateTime\TimeSpan::day
     * @expectedException \Packfire\Exception\InvalidArgumentException
     */
    public function testDayNegative()
    {
        $this->object->day(-3);
    }

    /**
     * @covers \Packfire\DateTime\TimeSpan::totalSeconds
     */
    public function testTotalSeconds()
    {
        $this->assertEquals(3695, $this->object->totalSeconds());
        $this->object->hour(2);
        $this->assertEquals(7295, $this->object->totalSeconds());
        $this->object->day(1);
        $this->assertEquals(93695, $this->object->totalSeconds());
    }

    /**
     * @covers \Packfire\DateTime\TimeSpan::totalMinutes
     */
    public function testTotalMinutes()
    {
        $this->assertEquals(3695 / 60, $this->object->totalMinutes());
        $this->object->hour(2);
        $this->assertEquals(7295 / 60, $this->object->totalMinutes());
        $this->object->day(1);
        $this->assertEquals(93695 / 60, $this->object->totalMinutes());
    }

    /**
     * @covers \Packfire\DateTime\TimeSpan::totalHours
     */
    public function testTotalHours()
    {
        $this->assertEquals(3695 / 3600, $this->object->totalHours());
        $this->object->hour(2);
        $this->assertEquals(7295 / 3600, $this->object->totalHours());
        $this->object->day(1);
        $this->assertEquals(93695 / 3600, $this->object->totalHours());
    }

    /**
     * @covers \Packfire\DateTime\TimeSpan::totalDays
     */
    public function testTotalDays()
    {
        $this->assertEquals(3695 / 86400, $this->object->totalDays());
        $this->object->hour(2);
        $this->assertEquals(7295 / 86400, $this->object->totalDays());
        $this->object->day(1);
        $this->assertEquals(93695 / 86400, $this->object->totalDays());
    }

    /**
     * @covers \Packfire\DateTime\TimeSpan::add
     */
    public function testAdd()
    {
        $ts = $this->object->add(new TimeSpan(90015));
        $this->assertEquals(2, $ts->hour());
        $this->assertEquals(1, $ts->day());
        $this->assertEquals(50, $ts->second());
        $this->assertEquals(1, $ts->minute());
    }

    /**
     * @covers \Packfire\DateTime\TimeSpan::subtract
     */
    public function testSubtract()
    {
        $ts = $this->object->subtract(new TimeSpan(1425));
        $this->assertEquals(0, $ts->hour());
        $this->assertEquals(0, $ts->day());
        $this->assertEquals(37, $ts->minute());
        $this->assertEquals(50, $ts->second());
    }
}
