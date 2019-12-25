<?php

/**
 * 
 * (c) Suurshater Gabriel <suurshater.ihyongo@st.futminna.edu.ng>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Artisan\Core;

trait Singleton
{

    /**
     * Instance of carrying class
     * @var self 
     */
    protected static $_instance = null;

    /**
     * Create an class instance based on singleton principle
     * @return self
     */
    public static function create()
    {
        return assign_return(static::$_instance, is_null(static::$_instance), new self());
    }

}
