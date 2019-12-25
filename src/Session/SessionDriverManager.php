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
 * Description of SessionDriverManager
 *
 * @author Cloud
 */
use RuntimeException;
use Artisan\Utility\Hash;
use InvalidArgumentException;
use Artisan\Session\Drivers\FileSessionDriver;
class SessionDriverManager
{
    /**
     * 
     * @param array $parameters
     * @return FileSession
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public static function create(array $parameters)
    {
        if(!Hash::exists('driver', $parameters)){
            throw new InvalidArgumentException('A session driver must be selected to session');
        }
        
        switch(strtolower($parameters['driver'])) {
            case 'file':
                return new FileSessionDriver($parameters);
        }
        
        throw new RuntimeException();
    }
}
