<?php

use Packfire\Application\Http\Response as HttpResponse;
use Packfire\Net\Http\ResponseCode;

/**
 * RedirectResponse class
 * 
 * A response that indicates that the browser should redirect to another URL.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.response
 * @since 1.0-sofia
 */
class RedirectResponse extends HttpResponse {
    
    /**
     * Create a new pRedirectResponse object
     * @param string|pUrl $url The URL to redirect to
     * @param string $code (optional) The HTTP code to use for the redirect.
     *                      Defaults to pHttpResponseCode::HTTP_302.
     * @since 1.0-sofia
     */
    public function __construct($url, $code = ResponseCode::HTTP_302){
        parent::__construct();
        $this->code($code);
        $this->headers()->add('Location', (string)$url);
    }
    
}