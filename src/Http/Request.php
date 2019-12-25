<?php

namespace Artisan\Http;

/**
 * Description of Request
 *
 * @author Cloud
 */

class Request
{
    //put your code here
    protected $headers;
    protected $environment;
    public function __construct($environment = null, Headers $headers = null)
    {
        $this->headers = $headers;
        $this->__initiateEnvironment(Environment::create($environment));
    }
    public function headers()
    {
        return $this->headers;
    }
    public function environment()
    {
        return $this->environment;
    }
    public function protocol()
    {
        return $this->environment->get('REQUEST_PROTOCOL');
    }
    public function functionName()
    {
        
    }
    /**
     * 
     * @param \Artisan\Http\Environment $environment
     * @return type
     */
    private function __initiateEnvironment(Environment $environment)
    {
        return $this->environment = $environment;
    }
}
