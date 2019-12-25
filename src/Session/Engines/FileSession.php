<?php

/**
 * 
 * (c) Suurshater Gabriel <suurshater.ihyongo@st.futminna.edu.ng>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Artisan\Session\Engines;

/**
 * Description of FileSession
 *
 * @author Cloud
 */
use SessionHandlerInterface;
class FileSession implements SessionHandlerInterface
{
    //put your code here
    private $path;
    private $configuration;
    public function __construct(array $parameters)
    {
        $this->configuration = $parameters;
        if( array_key_exists('save_path', $parameters)){
            ini_set('session.save_path', $parameters['save_path']);
        }
    }

    public function open($save_path, $session_name)
    {
        $this->path = $save_path;
        if (!is_dir($this->path)) {
            mkdir($this->path, 0777);
        }

        return true;
    }

    public function close()
    {
        return true;
    }

    public function read($id)
    {
        return (string)@file_get_contents("$this->path/sess_$id");
    }

    public function write($id, $data)
    {
        return file_put_contents("$this->path/sess_$id", $data) === false ? false : true;
    }

    public function destroy($id)
    {
        $file = "$this->path/sess_$id";
        if (file_exists($file)) {
            unlink($file);
        }

        return true;
    }

    public function gc($maxlifetime)
    {
        foreach (glob("$this->path/sess_*") as $file) {
            if (filemtime($file) + $maxlifetime < time() && file_exists($file)) {
                unlink($file);
            }
        }

        return true;
    }
}
