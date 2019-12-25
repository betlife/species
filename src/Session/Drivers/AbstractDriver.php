<?php

/**
 * 
 * (c) Suurshater Gabriel <suurshater.ihyongo@st.futminna.edu.ng>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Artisan\Session\Drivers;

/**
 * Description of AbstractDriver
 *
 * @author Cloud
 */
abstract class AbstractDriver
{
    protected $fingerprint = '';
    /**
     * Configuration parameters
     * 
     * @var array 
     */
    protected $configuration = [];
    /**
     * Create new instance of configuration
     * 
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }
    /**
     * Destroys cookie session
     * 
     * @return void
     */
    protected function _destroy()
    {
        $c = $this->configuration;
        setcookie($c['name'], null, time() - 1, $c['path'], $c['domain'], $c['https'], $c['httponly']);
    }
    /**
     * 
     * @return string
     */
    public function fingerprint()
    {
        return $this->fingerprint;
    }
    /**
     * 
     * @param string $data
     * @return string
     */
    protected function generatefingerprint($data)
    {
        $this->fingerprint = sha1($data);
        return $this;
    }
}
