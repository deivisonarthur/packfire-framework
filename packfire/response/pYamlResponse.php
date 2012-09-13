<?php
pload('packfire.application.http.pHttpAppResponse');
pload('packfire.yaml.pYamlWriter');
pload('packfire.text.pTextStream');
pload('IResponseFormat');

/**
 * pYamlResponse class
 * 
 * A response class that returns YAML format
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.response
 * @since 1.1-sofia
 */
class pYamlResponse extends pHttpAppResponse implements IResponseFormat {
    
    /**
     * Create a new pJsonResponse object
     * @param mixed $object The array or object that will be responded to the
     *                      client with
     * @since 1.1-sofia
     */
    public function __construct($object){
        parent::__construct();
        $this->headers()->add('Content-Type', 'application/x-yaml');
        if(is_string($object)){ // probably already encoded
            $this->body($object); 
        }else{
            $textStream = new pTextStream();
            $writer = new pYamlWriter($textStream);
            if(is_object($object)){
                $object = (array)$object;
            }
            $writer->write($object);
            $textStream->seek(0);
            $this->body($textStream->read($textStream->length()));
        }
    }
    
}