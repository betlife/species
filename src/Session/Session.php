<?php

/**
 * 
 * (c) Suurshater Gabriel <suurshater.ihyongo@st.futminna.edu.ng>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Artisan\Session;

/**
 * Description of Session
 *
 * @author Cloud
 */
use RuntimeException;
use SessionHandlerInterface;
use Artisan\Utility\Hash;
use Artisan\Contracts\Session\Session as SessionInterface;

class Session implements SessionInterface
{

    /**
     * Container of session configuration settings
     * @var array
     */
    protected $configuration = [];

    /**
     * A running instance of the session driver in use
     * 
     * @var \SessionHandlerInterface
     */
    protected $driver        = null;

    /**
     * Create a new session instance
     * 
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        if( boolval(ini_get('session.auto_start')) ) {
            throw new RuntimeException('not allowd');
            /**
             * @todo A log message stating that session auto start is enabled. Session operation will be disabled
             */
            return;
        }

        $this->configuration($configuration);
        $driver = SessionDriverManager::create($configuration);
        //throw_unless(($this->driver instanceof SessionHandlerInterface), new RuntimeException());
        if( !$driver instanceof SessionHandlerInterface ) {
            throw new RuntimeException('Session driver must be an instance of SessionHandlerInterface');
        }

        session_set_save_handler($driver, true);
        session_start();
    }

    /**
     * Name of the default session driver in use
     * @return string
     */
    public function getDriverName()
    {
        return $this->configuration['driver'];
    }

    /**
     * Determines if session key exists
     * @param string $key
     * @return bool
     */
    public function exists($key)
    {
        return Hash::exists($key, $_SESSION);
    }

    /**
     * Assigns session value to key
     * 
     * @param string $key
     * @param string|null $value
     * @return $this
     */
    public function set($key, $value = null)
    {
        if( is_array($key) ) {
            foreach($key as $k => $v) {
                $this->set($k, $v);
            }
            return $this;
        }
        $_SESSION[$key] = $value;

        return $this;
    }

    /**
     * Get the value of a session by key
     * 
     * @param string $key
     * @param mixed $default
     * @return string
     */
    public function get($key, $default = null)
    {
        return Hash::retrive($key, $_SESSION, $default);
    }

    /**
     * Delete a session by key
     * 
     * @param string $key
     * @return $this
     */
    public function delete($key)
    {
        if( is_array($key)){
            foreach($key as $k) {
                $this->delete($k);
            }
        }
        if( $this->exists($key) ) {
            unset($_SESSION[$key]);
        }

        return true;
    }
    /**
     * 
     * @return void
     */
    public function destroy()
    {
        session_destroy();
    }
    /**
     * 
     * @param bool $delete
     */
    public function regenerate(bool $delete = false)
    {
        $this->set('last', time() - 1);
        session_regenerate_id($delete);
    }
    /**
     * Configures session cookie options
     * 
     * @param array $c
     * @return void
     */
    protected function configuration($c)
    {
        if( Hash::exists('lifetime', $c) ) {
            $c['lifetime'] = (int) $c['lifetime'];
        }

        if( Hash::exists('name', $c) && empty($c['name']) ) {
            $c['name'] = ini_get('session_name');
        }
        else {
            echo 'set name';
            ini_set('session.name', $c['name']);
        }
        session_set_cookie_params($c['lifetime'], $c['path'], $c['domain'], $c['secure'], $c['httponly']);

        $this->configuration = $c;
        ini_set('session.use_trans_sid', 0);
        ini_set('session.use_strict_mode', 1);
        ini_set('session.use_cookies', 1);
        ini_set('session.use_only_cookies', 1);
    }
}
