<?php
pload('pLinqWorkerQuery');

/**
 * A LINQ Select Query
 * Re-map the values
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.plinq
 * @since 1.0-sofia
 */
class pLinqSelectQuery extends pLinqWorkerQuery {
    
    /**
     * Execute the query
     * @param array $collection The collection to execute upon
     * @return array Returns the resulting array after the query execution
     * @since 1.0-sofia
     */
    public function run($collection) {
       $result = array();
       $worker = $this->worker();
       foreach($collection as $element){
           $result[] = $worker($element);
       }
       return $result;
    }
}