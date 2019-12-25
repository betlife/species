<?php

/**
 * 
 * (c) Suurshater Gabriel <suurshater.ihyongo@st.futminna.edu.ng>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Artisan\Contracts\Session;

/**
 *
 * @author Cloud
 */
interface Session
{
    /**
     * Check if a session key exists
     * @param string $key
     */
    public function exists($key);
    /**
     * Set a session key with value
     * @param string $key
     * @param string $value
     */
    public function set($key, $value);
    /**
     * Get a session value using key
     * @param string $key
     * @param mixed $default
     */
    public function get($key, $default = null);
    /**
     * Delete a registered session with key
     * @param string $key
     */
    public function delete($key);
    /**
     * 
     * @return void Description
     */
    public function destroy();
    /**
     * 
     * @param bool $delete
     */
    public function regenerate(bool $delete = false);
}
