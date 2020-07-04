<?php defined('MONSTRA_ACCESS') or die('No direct script access.');

    /**
     *	Array Helper
     *
     *	@package Monstra
     *	@subpackage Helpers
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2012 Romanenko Sergey / Awilum
     *	@version $Id$
     *	@since 1.0.0
     *  @license http://opensource.org/licenses/gpl-license.php GNU Public License
     *  Monstra is free software. This version may have been modified pursuant
     *  to the GNU General Public License, and as distributed it includes or
     *  is derivative of works licensed under the GNU General Public License or
     *  other free or open source software licenses.
     *  See COPYING.txt for copyright notices and details.
     *  @filesource 
     */


    class Arr {
     

        /** 
         * Protected constructor since this is a static class.
         *
         * @access  protected
         */  
        protected function __construct() {
            // Nothing here
        }
           

        /**
         * Subval sort
         *
         *  <code>
         *      $new_array = Arr::subvalSort($old_array, 'sort');
         *  </code>
         *
         * @param array  $a      Array
         * @param string $subkey Key
         * @param string $order  Order type DESC or ASC
         * @return array
         */
        public static function subvalSort($a, $subkey, $order = null) {
            if (count($a) != 0 || (!empty($a))) {
                foreach ($a as $k=>$v) $b[$k] = function_exists('mb_strtolower') ? mb_strtolower($v[$subkey]) : strtolower($v[$subkey]);                
                if ($order==null || $order== 'ASC') asort($b); else if ($order == 'DESC') arsort($b);                
                foreach ($b as $key=>$val) $c[] = $a[$key];
                return $c;
            }
        }


        /**
         * Get a single key from an array. If the key does not exist in the
         * array, the default value will be returned instead.
         *
         *  <code>
         *      $login = Arr::get($_POST, 'login');
         *  </code>
         *
         * @param   array  $array   Array to extract from
         * @param   string $key     Key name
         * @param   mixed  $default Default value
         * @return  mixed
         */
        public static function get($array, $key, $default = null) {
            return isset($array[$key]) ? $array[$key] : $default;
        }
        
    }