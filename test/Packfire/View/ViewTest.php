<?php

namespace Packfire\View;

use Packfire\Collection\Map;
use Packfire\Route\Http\Route;
use Packfire\Route\Http\Router;
use Packfire\Template\Template;
use Packfire\FuelBlade\Container;

require_once 'test/Mocks/View.php';
require_once 'test/Mocks/Config.php';

use Packfire\Test\Mocks\View;
use Packfire\Test\Mocks\Config;

/**
 * Test class for View.
 * Generated by PHPUnit on 2012-03-25 at 13:34:52.
 */
class ViewTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Packfire\View\View
     */
    protected $object;
    private $ioc;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new View();
        $this->ioc = new Container();
        $bucket = $this->ioc;

        $router = new Router();
        $configData = new Map(array('rewrite' => '/home', 'actual' => 'Rest'));
        $router->add('home', new Route('home', $configData));
        $bucket['router'] = $router;

        $config = new Config();
        $bucket['config'] = $config;

        call_user_func($this->object, $this->ioc);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers \Packfire\View\View::create
     */
    public function testCreate()
    {
        $this->object->state(new Map(array('tag' => 'five  ')));
        $this->object->using(new Template('data: {tag} route: {route} {binder}'));
        $this->assertEquals('data: five route: http://example.com/test/home test2', $this->object->render());
    }
}
