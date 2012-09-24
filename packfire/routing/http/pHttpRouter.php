<?php
pload('packfire.routing.pRouter');
pload('pHttpRoute');
pload('pHttpRouter');
pload('packfire.net.http.pUrl');
pload('packfire.template.pTemplate');

/**
 * pHttpRouter class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.routing.http
 * @since 1.0-elenor
 */
class pHttpRouter extends pRouter {
    
    /**
     * Whether HTTP routing is enabled or not
     * @var boolean
     * @since 1.0-elenor
     */
    private $enabled = true;
    
    /**
     * Perform loading of routes from the routing configuration file
     * @since 1.0-elenor
     */
    public function load(){
        parent::load();
        if($this->service('config.app')){
            $this->enabled = $this->service('config.app')->get('routing', 'enabled');
        }
        $config = new pMap(array(
            'rewrite' => '/{class}/{action}',
            'actual' => 'directControllerAccessRoute',
            'method' => null,
            'params' => new pMap(array(
                'class' => 'regex/`^([a-zA-Z0-9\_]+)$`',
                'action' => 'regex/`^([a-zA-Z0-9\_]+)$`'
            ))
        ));
        $directControllerAccessRoute = new pHttpRoute(
                'packfire.directControllerAccess',
                $config);
        $this->add('packfire.directControllerAccess', $directControllerAccessRoute);
    }
    
    /**
     * Factory manufature the route based on the configuration
     * @param string $key Name of the route
     * @param pMap $data The configuration of the route
     * @return IRoute Returns the route manufactured
     * @since 1.0-elenor
     */
    protected function routeFactory($key, $data){
        if($data->get('redirect')){
            $route = new pHttpRedirectRoute($key, $data);
        }else{
            $route = new pHttpRoute($key, $data);
        }
        return $route;
    }
    
    /**
     * Prepare a route with the parameters
     * @param pHttpRoute $route The route to be prepared
     * @param array|pMap $params The parameters to prepare
     * @return string The final route URL
     * @since 1.0-elenor
     */
    protected function prepareRoute($route, $params){
        $template = new pTemplate($route->rewrite());
        foreach($params as $name => $value){
            $template->fields()->add($name, pUrl::encode($value));
        }

        return $template->parse();
    }
    
}