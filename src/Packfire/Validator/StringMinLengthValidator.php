<?php
namespace Packfire\Validator;

use Packfire\Validator\IValidator;

/**
 * StringMinLengthValidator class
 * 
 * String minimum length validator
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Validator
 * @since 1.0-sofia
 */
class StringMinLengthValidator implements IValidator {
    
    /**
     * The minimum length of the string 
     * @var string 
     * @since 1.0-sofia
     */
    private $length;
    
    /**
     * Create a new StringMinLengthValidator object
     * @param string $length The minimum length of the string
     * @since 1.0-sofia
     */
    public function __construct($length){
        $this->length = $length;
    }
    
    /**
     * Validate the value
     * @param mixed $value The value to validate
     * @return boolean Returns true if the validation succeeded,
     *                        false otherwise.
     * @since 1.0-sofia
     */
    public function validate($value) {
        return strlen($value) >= $this->length;
    }
    
}