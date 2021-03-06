<?php
namespace Packfire\IO\File;

/**
 * Test class for InputStream.
 * Generated by PHPUnit on 2012-06-28 at 02:17:03.
 */
class InputStreamTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Packfire\IO\File\InputStream
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new InputStream(__FILE__);
        $this->object->open();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->object->close();
    }

    /**
     * @covers \Packfire\IO\File\InputStream::pathname
     */
    public function testPathname()
    {
        $this->assertEquals(__FILE__, $this->object->pathname());
    }

    /**
     * @covers \Packfire\IO\File\InputStream::length
     */
    public function testLength()
    {
        $this->assertEquals(filesize(__FILE__), $this->object->length());
    }

    /**
     * @covers \Packfire\IO\File\InputStream::read
     */
    public function testRead()
    {
        $this->assertEquals('<?php', $this->object->read(5));
    }

    /**
     * @covers \Packfire\IO\File\InputStream::seek
     */
    public function testSeek()
    {
        $this->object->seek(2);
        $this->assertEquals('php', $this->object->read(3));
    }

    /**
     * @covers \Packfire\IO\File\InputStream::seekable
     */
    public function testSeekable()
    {
        $this->assertTrue($this->object->seekable());
    }

    /**
     * @covers \Packfire\IO\File\InputStream::tell
     */
    public function testTell()
    {
        $this->assertEquals(0, $this->object->tell());
        $this->object->seek(3);
        $this->assertEquals(3, $this->object->tell());
        $this->object->seek(2);
        $this->assertEquals(2, $this->object->tell());
    }
}
