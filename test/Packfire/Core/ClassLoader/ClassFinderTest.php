<?php
namespace Packfire\Core\ClassLoader;

/**
 * Test class for ClassFinder.
 * Generated by PHPUnit on 2012-10-13 at 10:20:26.
 */
class ClassFinderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Packfire\Core\ClassLoader\ClassFinder
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new ClassFinder;
        $this->object->addNamespace('Packfire', 'src');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers \Packfire\Core\ClassLoader\ClassFinder::addNamespace
     */
    public function testAddNamespace()
    {
        $this->object->addNamespace('Packfire', 'vendor');
        $property = new \ReflectionProperty('Packfire\Core\ClassLoader\ClassFinder', 'namespaces');
        $property->setAccessible(true);
        $namespaces = $property->getValue($this->object);
        $this->assertCount(1, $namespaces);
        $this->assertEquals(array('src', 'vendor'), $namespaces['Packfire']);
    }

    /**
     * @covers \Packfire\Core\ClassLoader\ClassFinder::find
     */
    public function testFind()
    {
        $class = 'Packfire\Core\ClassLoader\ClassFinder';
        $file = $this->object->find($class);
        $refl = new \ReflectionClass($class);
        $this->assertFileExists($file);
        $this->assertEquals($refl->getFileName(), realpath($file));
    }
}
