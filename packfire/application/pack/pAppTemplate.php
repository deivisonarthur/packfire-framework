<?php

/**
 * pAppTemplate class
 * 
 * Performs template loading for application
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.application.pack
 * @since 1.1-sofia
 */
class pAppTemplate {
    
    /**
     * Load a template from the template folder
     * @param string $name Name of the template to load
     * @return ITemplate Returns the template
     * @since 1.0-sofia
     * 
     * @todo complete template callback
     */
    public static function load($name){
        $path = __APP_ROOT__ . 'pack/template/' . $name;
        
        // parsers
        $extensions = array(
            'html' => 'packfire.template.moustache.pMoustacheTemplate',
            'htm' => 'packfire.template.moustache.pMoustacheTemplate',
            'php' => 'packfire.template.pPhpTemplate'
        );
        
        $template = null;
        foreach($extensions as $type => $package){
            if(is_file($path . '.' .  $type)){
                $fileContent = file_get_contents($path . '.' .  $type);
                pload($package);
                list($package, $class) = pClassLoader::resolvePackageClass($package);
                $template = new $class($fileContent);
            }
        }
        
        return $template;
    }
    
}