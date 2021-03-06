<?php

/**
 * Packfire Framework for PHP
 * By Sam-Mauris Yong
 *
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong <sam@mauris.sg>
 * All rights reserved.
 */

namespace Packfire\Application\Http;

use Packfire\Application\ServiceApplication;
use Packfire\Exception\HttpException;
use Packfire\Exception\MissingDependencyException;
use Packfire\Controller\Invoker as ControllerInvoker;
use Packfire\Net\Http\Method as HttpMethod;
use Packfire\Collection\Map;
use Packfire\Route\Http\Route;
use Packfire\Route\CacheRouter;

/**
 * The default web serving application
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Application\Http
 * @since 1.0-elenor
 */
class Application extends ServiceApplication
{
    /**
     * Perform service loading processing
     * @param \Packfire\FuelBlade\Container $container
     * @since 2.1.0
     */
    public function __invoke($container)
    {
        parent::__invoke($container);

        $loader = new ServiceLoader();
        $loader($this->ioc);

        return $this;
    }

    /**
     * Process a request and prepare the response
     * @since 2.1.0
     */
    public function process()
    {
        $request = $this->ioc['request'];
        $oriMethod = $request->method();
        if ($request->headers()->keyExists('X-HTTP-Method')) {
            $oriMethod = $request->headers()->get('X-HTTP-Method');
        }
        if ($request->headers()->keyExists('X-HTTP-Method-Override')) {
            $oriMethod = $request->headers()->get('X-HTTP-Method-Override');
        }
        $request->method($oriMethod);

        if (!isset($this->ioc['router'])) {
            throw new MissingDependencyException('Router service required, but missing.');
            return;
        }
        $router = $this->ioc['router'];
        /* @var $router \Packfire\Route\Router */

        $debugMode = isset($this->ioc['config'])
                && $this->ioc['config']->get('app', 'debug');
        if ($debugMode) {
            $config = new Map(
                array(
                    'rewrite' => '/{path}',
                    'actual' => 'directControllerAccessRoute',
                    'method' => null,
                    'params' => new Map(
                        array( 'path' => 'any' )
                    )
                )
            );
            $router->add(
                'packfire.directControllerAccess',
                new Route(
                    'packfire.directControllerAccess',
                    $config
                )
            );
        }

        if ($request->method() == HttpMethod::GET
                && isset($this->ioc['config'])
                && isset($this->ioc['cache'])
                && $this->ioc['config']->get('router', 'caching')) {
            $this->ioc['router'] = $this->ioc->share(
                function ($c) use ($router) {
                    return new CacheRouter($router, $c['cache']);
                }
            );
            $router = $this->ioc['router'];
        }

        /* @var $route Route */
        $route = $router->route($request);
        $this->ioc['route'] = $route;
        $this->ioc['response'] = new Response();

        if ($route) {
            if (is_string($route->actual()) && strpos($route->actual(), ':')) {
                list($class, $action) = explode(':', $route->actual());
            } else {
                $class = $route->actual();
                $action = '';
            }

            if ($debugMode && $route->name() == 'packfire.directControllerAccess') {
                $caLoader = $this->directAccessProcessor();
            } else {
                $caLoader = new ControllerInvoker($class, $action);
            }
            $caLoader($this->ioc);
            if (!$caLoader->load()) {
                throw new HttpException(404);
            }
        } else {
            throw new HttpException(404);
        }
    }

    /**
     * Handles unhandled exception in the application execution
     * @param Exception $exception The unhandled exception
     * @since 1.0-sofia
     */
    public function handleException($exception)
    {
        $this->ioc['exception.handler']->handle($exception);
    }

    /**
     * Callback for Direct Controller Access Routing
     * @return ControllerInvoker Returns the loader
     * @since 1.0-sofia
     */
    public function directAccessProcessor()
    {
        $route = $this->ioc['route'];
        $path = $route->params()->get('path');
        $route->params()->removeAt('path');
        $class = '\\' . str_replace('/', '\\', dirname($path));
        $action = basename($path);
        $caLoader = new ControllerInvoker($class, $action);

        return $caLoader;
    }
}
