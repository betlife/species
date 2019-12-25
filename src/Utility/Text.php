<?php

namespace Artisan\Utility;

/**
 * Description of Text
 *
 * @author Cloud
 */
class Text
{
    /**
     * 
     * @param string $pattern
     * @param string $container
     * @return bool
     */
   public static function match($pattern, $container)
   {
       return preg_match($pattern, $container);
   }
   /**
    * 
    * @param string $item
    * @param string $haystack
    * @return bool
    */
   public static function contains($item, $haystack)
   {
       return (bool) substr_count($haystack, $item);
   }
}
