<?php

pload('packfire.routing.http.pHttpRouter');
pload('packfire.collection.pMap');
pload('packfire.routing.http.pHttpRoute');
require_once('mocks/tMockRouteRequest.php');

/**
 * Test class for pHttpRouter.
 * Generated by PHPUnit on 2012-03-25 at 13:34:52.
 */
class pHttpRouterTest extends PHPUnit_Framework_TestCase {

    /**
     * @var pHttpRouter
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new pHttpRouter();
        $config = new pMap(array('rewrite' => '/home', 'actual' => 'Rest'));
        $this->object->add('route.home', new pHttpRoute('route.home', $config));
        $config = new pMap(array('rewrite' => '/home/{data}', 'actual' => 'Rest', 'method' => null, 'params' => array('data' => 'int')));
        $this->object->add('route.homeData', new pHttpRoute('route.homeData', $config));
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers pRouter::add
     */
    public function testAdd() {
        $this->assertCount(2, $this->object->entries());
        $config = new pMap(array('rewrite' => '/test', 'actual' => 'Rest:cool'));
        $this->object->add('route.test', new pHttpRoute('route.test', $config));
        $this->assertCount(3, $this->object->entries());
        $this->assertEquals('/test', $this->object->entries()->get('route.test')->rewrite());
    }

    /**
     * @covers pRouter::entries
     */
    public function testEntries() {
        $this->assertCount(2, $this->object->entries());
        $this->assertInstanceOf('pMap', $this->object->entries());
    }

    /**
     * @covers pHttpRouter::route
     */
    public function testRoute() {
        $request = new tMockRouteRequest('home/200',
                array('PHP_SELF' => 'index.php/home/200', 'SCRIPT_NAME' => 'index.php'));
        $route = $this->object->route($request);
        $this->assertInstanceOf('pHttpRoute', $route);
        $this->assertEquals(200, $route->params()->get('data'));
    }

    /**
     * @covers pHttpRouter::route
     */
    public function testRoute2() {
        $request = new tMockRouteRequest('home/500',
                array('PHP_SELF' => 'index.php/home/500', 'SCRIPT_NAME' => 'index.php'));
        $route = $this->object->route($request);
        $this->assertInstanceOf('pHttpRoute', $route);
        $this->assertEquals('500', $route->params()->get('data'));
    }

    /**
     * @covers pHttpRouter::route
     */
    public function testRoute3() {
        $request = new tMockRouteRequest('home/a',
                array('PHP_SELF' => 'index.php/home/a', 'SCRIPT_NAME' => 'index.php'));
        $route = $this->object->route($request);
        $this->assertNull($route);
    }
    
    /**
     * @covers pHttpRouter::to
     */
    public function testTo() {
        $this->assertEquals('/home', $this->object->to('route.home'));
    }

}

