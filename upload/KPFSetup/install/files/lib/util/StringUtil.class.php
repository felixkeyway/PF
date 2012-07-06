<?php
namespace kpf\util;

/**
 * This class provides some util methods for string handling.
 * 
 * @author          Felix Jacobi <jacobi@keyway-xelonis.com>
 * @copyright       2011-2012 Keyway-Xelonis GmbH
 * @license         GNU Lesser General Public License <http://www.gnu.org/licenses/lgpl.html>
 * @package         com.keyway-xelonis.kpf
 * @subpackage      util
 * @category        Portal Framework
 */

class StringUtil {
    /**
     * Adds escape slashes (\) to a string.
     * 
     * @param           string          $string
     * @return          string          $string 
     */
    
    public function addEscapeSlashes($string) {
        if (is_string($string)) :
            return addslashes($string);
        else :
            return $string;
        endif;
    }
    
    /**
     * Removes escape slashes from a string.
     * 
     * @param           string          $string
     * @return          string          $string
     */
    
    public function removeEscapeSlashes($string) {
        if (is_string($string)) :
            return stripcslashes($string);
        else :
            return $string;
        endif;
    }
}
?>