<?php

pload('packfire.controller.pCALoader');
pload('packfire.application.http.pHttpAppRequest');
pload('packfire.application.http.pHttpAppResponse');
pload('packfire.application.IAppResponse');
pload('packfire.ioc.pServiceBucket');
pload('packfire.session.pSession');
pload('packfire.routing.http.pHttpRoute');
pload('packfire.routing.http.pHttpRouter');
require_once('mocks/tMockSessionStorage.php');

/**
 * Test class for pCALoader.
 * Generated by PHPUnit on 2012-09-03 at 05:56:15.
 */
class pCALoaderTest extends PHPUnit_Framework_TestCase {

    /**
     * @var pCALoader
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $request = new pHttpAppRequest(null, null);
        $request->method('GET');
        $this->object = new pCALoader(
                        'packfire.welcome.HomeController',
                        'index',
                        $request,
                        new pHttpRoute('test', array()),
                        new pHttpAppResponse()
        );
        $bucket = new pServiceBucket();
        $storage = new tMockSessionStorage();
        $bucket->put('session.storage', $storage);
        $bucket->put('session', new pSession($storage));
        $bucket->put('loader', $this->object);
        $router = new pHttpRouter();
        $config = new pMap(array('rewrite' => 'home/{theme}', 'actual' => 'Rest'));
        $router->add('home', new pHttpRoute('route.home', $config));
        $router->add('themeSwitch', new pHttpRoute('route.home', $config));
        $bucket->put('router', $router);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers pCALoader::load
     */
    public function testLoad() {
        $this->assertTrue($this->object->load());
        $this->assertInstanceOf('IAppResponse', $this->object->response());
        $this->assertNotEmpty($this->object->response()->body());
    }

}