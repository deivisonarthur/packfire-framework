<?php
namespace Packfire\Template\Mustache;

use Packfire\IO\File\File as RealFile;
use Packfire\Template\Mustache\Template;

/**
 * File class
 * 
 * A moustache template file
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Template\Mustache
 * @since 1.1-sofia
 */
class File extends Template {
    
    /**
     * Create a new pMoustacheFile object
     * @param pFile|string $file The file or pathname to the file
     * @since 1.1-sofia
     */
    public function __construct($file) {
        if($file instanceof RealFile){
            $file = $file->pathname();
        }
        parent::__construct(file_get_contents($file));
    }
    
}