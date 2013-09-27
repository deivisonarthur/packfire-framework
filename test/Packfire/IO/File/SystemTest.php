<?php
namespace Packfire\IO\File;

/**
 * Test class for System.
 * Generated by PHPUnit on 2012-06-28 at 02:51:24.
 */
class SystemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers \Packfire\IO\File\System::fileExists
     */
    public function testFileExists()
    {
        $this->assertTrue(System::fileExists(__FILE__));
        $this->assertFalse(System::fileExists(__DIR__));
        $this->assertFalse(System::fileExists(__FILE__ . 'lalala'));
    }

    /**
     * @covers \Packfire\IO\File\System::pathExists
     */
    public function testPathExists()
    {
        $this->assertTrue(System::pathExists(__DIR__));
        $this->assertFalse(System::pathExists(__FILE__));
        $this->assertFalse(System::pathExists(__DIR__ . 'lalala'));
    }

    /**
     * @covers \Packfire\IO\File\System::freeSpace
     */
    public function testFreeSpace()
    {
        $this->assertInternalType('float', System::freeSpace(__DIR__));
    }

    /**
     * @covers \Packfire\IO\File\System::totalSpace
     */
    public function testTotalSpace()
    {
        $this->assertInternalType('float', System::totalSpace(__DIR__));
    }

    /**
     * @covers \Packfire\IO\File\System::pathSearch
     */
    public function testPathSearch()
    {
        $pattern = 'p*.php';
        $list = System::pathSearch($pattern)->toArray();
        $list2 = glob($pattern);
        $this->assertEquals($list2, $list);
    }

    /**
     * @covers \Packfire\IO\File\System::pathSearch
     */
    public function testPathSearch2()
    {
        $pattern = '*.php';
        $list = System::pathSearch($pattern, GLOB_NOSORT)->toArray();
        $list2 = glob($pattern, GLOB_NOSORT);
        $this->assertEquals($list2, $list);
    }
}
