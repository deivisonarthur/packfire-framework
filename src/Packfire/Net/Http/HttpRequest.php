<?php
pload('packfire.text.pNewline');
pload('packfire.text.pTextStream');
pload('pUrl');
pload('packfire.collection.pMap');
pload('pHttpMethod');

/**
 * A HTTP Request
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.net.http
 * @since 1.0-sofia
 */
class pHttpRequest {
    
    /**
     * The method of request, e.g. GET, POST, HEAD
     * @var string
     * @since 1.0-sofia
     */
    protected $method;

    /**
     * The requested path / URI e.g. /example/path/to/file.php
     * @var string
     * @since 1.0-sofia
     */
    protected $uri;

    /**
     * The HTTP version called upon e.g. HTTP/1.0 or HTTP/1.1
     * @var string
     * @since 1.0-sofia
     */
    protected $version;

    /**
     * Body of the Request
     * @var IInputStream
     * @since 1.0-sofia
     */
    protected $body;

    /**
     * DateTime the request was made
     * @var pDateTime
     */
    protected $time;

    /**
     * An array of the HTTP headers in the HTTP Response
     * @var pMap
     * @since 1.0-sofia
     */
    protected $headers;

    /**
     * Whether the request is via HTTPS or not
     * @var boolean
     * @since 1.0-sofia
     */
    protected $https;

    /**
     * Cookies pertaining to this request
     * @var pMap
     * @since 1.0-sofia
     */
    protected $cookies;

    /**
     * An array of POST data related to this request
     * @var pMap
     * @since 1.0-sofia
     */
    protected $post;

    /**
     * An array of GET data related to this request
     * @var pMap
     * @since 1.0-sofia
     */
    protected $get;
    
    /**
     * Create the pHttpRequest object
     */
    public function __construct(){
        $this->cookies = new pMap();
        $this->post = new pMap();
        $this->get = new pMap();
        $this->headers = new pMap();
    }
    
    /**
     * Parse the string format of the HTTP request into this object
     * @param string $strRequest The string to be parsed
     * @since 1.0-sofia
     */
    public function parse($strRequest){
        $strRequest = pNewline::neutralize($strRequest);
        $lines = explode(pNewline::UNIX, $strRequest);
        if(count($lines) > 0){
            $requestLine = $lines[0];
            list($method, $uri, $version) = explode(' ', $requestLine);
            $this->method(trim($method));
            $parsedUri = new pMap(parse_url($uri));
            $this->uri($parsedUri->get('path', '/'));
            $getData = array();
            parse_str($parsedUri->get('query', ''), $getData);
            $this->get->append($getData);
            $this->version = trim($version);
            unset($lines[0]);
            $body = null;
            foreach($lines as $line){
                if(strlen($line) > 0){
                    if($body === null){
                        $separator = strpos($line, ':');
                        if($separator){
                            $key = trim(substr($line, 0, $separator));
                            $value = trim(substr($line, $separator + 1));
                            if($this->headers()->keyExists($key)){
                                $firstValue = $this->headers->get($key);
                                $this->headers->add($key, new ArrayList());
                                $this->headers->get($key)->add($firstValue);
                                $this->headers->get($key)->add($value);
                            }else{
                                $this->headers->add($key, $value);
                            }
                        }
                    }else{
                        $body .= $line . pNewline::UNIX;
                    }
                }else{
                    $body = '';
                }
            }
            if($body !== null){
                $contentType = $this->headers->get('Content-Type');
                if(strtolower($this->method) == 'post'){
                    if(substr($contentType, 0, 19) == 'multipart/form-data'){
                        // todo multipart form data parsing
                    }else{
                        $data = array();
                        parse_str(trim($body), $data);
                        $this->post->append($data);
                    }
                }else{
                    $this->body(new pTextStream($body));
                }
            }
        }
    }

    /**
     * Get the first Request line
     * @return string Returns the line
     * @since 1.0-sofia
     */
    public function requestLine() {
        return $this->method() . ' ' . $this->uri() . ' ' . $this->version();
    }

    /**
     * Get or set the URI of the HTTP request
     * @param string $uri (optional) If set, the new value will be set.
     * @return string Returns the URI of the request
     * @since 1.0-sofia
     */
    public function uri($uri = null){
        if(func_num_args() == 1){
            $this->uri = $uri;
        }
        return $this->uri;
    }

    /**
     * Get or set the method of the HTTP request
     * @param string $m (optional) Set the method
     * @return string Returns the method of the request
     * @since 1.0-sofia
     */
    public function method($m = null){
        if(func_num_args() == 1){
            $this->method = $m;
        }
        return $this->method;
    }

    /**
     * Get or set the version of the HTTP request
     * @param string $v (optional) If set, the new value will be set.
     * @return string Returns the HTTP request version
     * @since 1.0-sofia
     */
    public function version($v = null){
        if(func_num_args() == 1){
            $this->version = $v;
        }
        return $this->version;
    }

    /**
     * Get or set the input stream that reads body of the HTTP request
     * @param IInputStream $b (optional) If set, the new value will be set.
     * @return IInputStream Returns the input stream
     * @since 1.0-sofia
     */
    public function body($b = null){
        if(func_num_args() == 1){
            $this->body = $b;
        }
        return $this->body;
    }

    /**
     * Get or set the date/time of the HTTP request
     * @param pDateTime $t (optional) If set, the new value will be set.
     * @return pDateTime Returns the date time of the request
     * @since 1.0-sofia
     */
    public function time($t = null){
        if(func_num_args() == 1){
            $this->time = $t;
        }
        return $this->time;
    }

    /**
     * Get or set whether this request is HTTPS or not
     * @param boolean $h (optional) If set, the new value will be set.
     * @return boolean Returns whether the request is HTTPS or not
     * @since 1.0-sofia
     */
    public function https($h = null){
        if(func_num_args() == 1){
            $this->https = $h;
        }
        return $this->https;
    }
    
    /**
     * Get the hash map of GET parameters
     * @return pMap Returns the hash map
     * @since 1.0-sofia
     */
    public function get(){
        return $this->get;
    }
    
    /**
     * Get the hash map of POST parameters
     * @return pMap Returns the hash map
     * @since 1.0-sofia
     */
    public function post(){
        return $this->post;
    }
    
    /**
     * Get the hash map of cookies
     * @return pMap Returns the hash map of pHttpCookie
     * @since 1.0-sofia
     */
    public function cookies(){
        return $this->cookies;
    }
    
    /**
     * Get the hash map of headers
     * @return pMap Returns the hash map
     * @since 1.0-sofia
     */
    public function headers(){
        return $this->headers;
    }

    /**
     * Get this request's query string
     * @return string Returns the query string
     * @since 1.0-sofia
     */
    public function queryString() {
        return http_build_query($this->get()->toArray(), '', '&');
    }

    /**
     * Returns the full URL of this request
     * @return pUrl Returns the URL
     * @since 1.0-sofia
     */
    public function url(){
        $u = new pUrl();
        $u->host($this->headers->get('Host'));
        $u->scheme('http' . ($this->https() ? 's' : ''));
        $markpos = strpos($this->uri(), '?');
        if($markpos !== false){
            $u->path(substr($this->uri(), 0, $markpos));
        }else{
            $u->path($this->uri());
        }
        $u->params($this->get());
        return $u;
    }
    
    public function __toString(){
        $buffer = '';
        $buffer .= $this->requestLine() . pNewLine::UNIX;
        foreach ($this->headers() as $k => $h) {
            if (is_array($h)) {
                foreach ($h as $d) {
                    $buffer .= $k . ': ' . $d . pNewLine::UNIX;
                }
            } else {
                    $buffer .= $k . ': ' . $h . pNewLine::UNIX;
            }
        }
        if($this->body()){
            $buffer .= pNewLine::UNIX;
            while($this->body()->tell() < $this->body()->length()){
                $read = $this->body()->read(1024);
                $buffer .= $read;
            }
        }
        return $buffer;
    }
    
}