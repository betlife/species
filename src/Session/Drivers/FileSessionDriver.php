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
 * Description of FileDriver
 *
 * @author Cloud
 */
use RuntimeException;
use SessionHandlerInterface;

class FileSessionDriver extends AbstractDriver implements SessionHandlerInterface
{

    /**
     *
     * @var string 
     */
    public $path      = '';

    /**
     *
     * @var resource
     */
    protected $handle = null;

    /**
     * 
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        parent::__construct($configuration);
        if( array_key_exists('save_path', $configuration) ) {
            ini_set('session.save_path', $configuration['save_path']);
        }
    }

    /**
     * 
     * @return boolean
     */
    public function close()
    {
        file_unlock_and_close($this->handle);
        return true;
    }

    /**
     * 
     * @param string $path
     * @param string $name
     * @throws RuntimeException
     */
    public function open($path, $name)
    {
        if( !is_dir($path) ) {
            if( !mkdir($path, 0700, true) ) {
                throw new RuntimeException();
            }
        }

        if( !is_writable($path) ) {
            throw new RuntimeException();
        }

        $this->path                       = $path;
        $this->configuration['save_path'] = rtrim($path, DIRECTORY_SEPARATOR);

        return true;
    }

    /**
     * 
     * @param string $identifier
     * @return string
     * @throws RuntimeException
     */
    public function read($identifier)
    {
        $file = merge_filename('sess_' . $identifier, $this->path);
        if( is_null($this->handle) ) {
            $new          = !file_exists($file);
            $this->handle = fopen($file, 'c+b');

            if( !$this->handle ) {
                /*
                 * @todo replace with log message
                 */
                throw new RuntimeException;
            }
            // Prevents simultaneous access to session data file
            if( !flock($this->handle, LOCK_EX) ) {
                /*
                 * @todo replace with log message
                 */
                file_unlock_and_close($this->handle);
                throw new RuntimeException;
            }
            if( $new ) {
                chmod($file, 0600);
                $this->generatefingerprint('');
                return '';
            }
        }
        $chunks = '';
        for($read = 0, $length = filesize($file); $read < $length; $read += strlen($buffer)) {
            $buffer = fread($this->handle, $length - $read);
            if( !$buffer ) {
                break;
            }

            $chunks .= $buffer;
        }
        parent::generatefingerprint($chunks);
        flock($this->handle, LOCK_UN);
        return $chunks;
    }

    /**
     * 
     * @param type $identifier
     * @param type $data
     * @return boolean
     */
    public function write($identifier, $data)
    {
        if( !is_resource($this->handle) ) {
            return false;
        }
        $file = merge_filename('sess_' . $identifier, $this->path);
        file_unlock_and_close($this->handle);

        if( hash_equals($this->fingerprint, $data) ) {
            return touch($file);
        }
        if( !(bool) file_put_contents($file, $data, LOCK_EX) ) {
            /**
             * @todo add error log message
             */
            return false;
        }

        $this->generatefingerprint($data);
        return true;
    }

    /**
     * 
     * @param string $identifier
     * @return boolean
     */
    public function destroy($identifier)
    {
        $file = merge_filename($identifier, $this->path);
        if( file_exists($file) ) {
            unlink($file);
        }

        return true;
    }

    /**
     * 
     * @param int $maxlifetime
     * @return boolean
     */
    public function gc($maxlifetime)
    {
        $file = merge_filename('sess_*', $this->path);
        foreach(glob($file) as $file) {
            if( filemtime($file) + $maxlifetime < time() && file_exists($file) ) {
                unlink($file);
            }
        }

        return true;
    }

}
