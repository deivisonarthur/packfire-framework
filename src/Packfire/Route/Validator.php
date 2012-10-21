<?php
namespace Packfire\Route;

use Packfire\Collection\ArrayList;
use Packfire\Validator\SerialValidator;
use Packfire\Validator\NumericValidator;
use Packfire\Validator\MatchValidator;
use Packfire\Validator\RegexValidator;
use Packfire\Validator\CallbackValidator;
use Packfire\Validator\EmailValidator;

/**
 * Validator class
 *
 * Routing parameter validator
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package Packfire\Route
 * @since 2.0.0
 */
class Validator {
    
    /**
     * Validate an array of data
     * @param ArrayList|array $rules The list of rules defined
     * @param ArrayList|array $data The data to be validated
     * @param Map|array $params (reference) The output parameters
     * @param boolean $validation (reference, optional) The validation boolean
     * @return boolean Returns true if validation is successful, false otherwise.
     * @since 2.0.0
     */
    public static function validate($rules, $data, &$params, &$validation = true){
        foreach($data as $key => $value){
            if(is_array($value)){
                $param = array();
                self::validate($rules, $value, $param, $validation);
                $params[$key] = $param;
            }elseif(isset($rules[$key])){
                $validation = self::validateParam($rules[$key], $value, $data);
                $params[$key] = $value;
            }
        }
        return $validation;
    }

    /**
     * Validate a value based on the given rule
     * @param string|ArrayList|array $rule The name of the validation rule(s)
     * @param mixed &$value The value to be validated
     * @return boolean Returns true if the validation succeeded, false otherwise.
     * @since 2.0.0
     */
    protected static function validateParam($rules, &$value, &$data){
        if(is_array($rules)){
            $rules = new ArrayList($rules);
        }
        if(is_string($rules)){
            $rules = new ArrayList(array($rules));
        }

        // optional parameter and nothing supplied
        if($value === null){
            if($rules->contains('optional')){
                return true;
            }else{
                return false;
            }
        }

        $validator = new SerialValidator();
        $original = $value;
        foreach($rules as $rule){
            $slashPos = strpos($rule, '/');
            $options = '';
            if($slashPos !== false){
                $options = substr($rule, $slashPos + 1);
                $rule = substr($rule, 0, $slashPos);
            }
            switch($rule){
                case 'any':
                    break;
                case 'numeric':
                case 'number':
                case 'num':
                    $validator->add(new NumericValidator());
                    $value += 0;
                    break;
                case 'float':
                case 'real':
                case 'double':
                    $validator->add(new NumericValidator());
                    $validator->add(new CallbackValidator(function($value){
                        return is_float($value + 0);
                    }));
                    $value += 0;
                    break;
                case 'integer':
                case 'int':
                case 'long':
                    $validator->add(new NumericValidator());
                    $validator->add(new CallbackValidator(function($value){
                        return is_int($value + 0);
                    }));
                    $value += 0;
                    break;
                case 'min':
                    $min = $options + 0;
                    $validator->add(new CallbackValidator(function($x) use ($min){
                        return $x >= $min;
                    }));
                    break;
                case 'max':
                    $max = $options + 0;
                    $validator->add(new CallbackValidator(function($x) use ($max){
                        return $x <= $max;
                    }));
                    break;
                case 'bool':
                case 'boolean':
                    $validator->add(
                        new MatchValidator(array('true', 'false', '0', '1', 'on', 'off', 'y', 'n'))
                    );
                    $value = in_array($value, array('true', '1', 'on', 'y'), true);
                    break;
                case 'alnum':
                    $options = '/^[a-zA-Z0-9]+$/';
                    $validator->add(new RegexValidator($options));
                    break;
                case 'strmin':
                    $min = $options + 0;
                    $validator->add(new CallbackValidator(function($x) use ($min){
                        return strlen($x) >= $min;
                    }));
                    break;
                case 'strmax':
                    $max = $options + 0;
                    $validator->add(new CallbackValidator(function($x) use ($max){
                        return strlen($x) <= $max;
                    }));
                    break;
                case 'email':
                    $validator->add(new EmailValidator());
                    break;
                case 'alpha':
                    $options = '/^[a-zA-Z]+$/';
                    $validator->add(new RegexValidator($options));
                    break;
                case 'regex':
                    $validator->add(new RegexValidator($options));
                    break;
                case 'param':
                    $match = isset($data[$options])
                                    ? $data[$options] : null;
                    $validator->add(new MatchValidator($match));
                    break;
                case 'equal':
                case 'equals':
                case 'value':
                    $value = $options;
                    break;
                case 'optional':
                    break;
                default:
                    $validator->add(new MatchValidator($rule));
                    break;
            }
        }
        return $validator->validate($original);
    }   
    
}