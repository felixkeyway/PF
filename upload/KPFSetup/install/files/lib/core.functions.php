<?php
/**
 * In this file are contains functions for the core.
 * 
 * @author          Felix Jacobi
 * @copyright       2011-2012 Keyway-Xelonis
 * @license         GNU Lesser General Public License <http://www.gnu.org/licenses/lgpl.html>
 * @package         com.keyway-xelonis.kpf
 * @category        Portal Framework 
 */

// set type of the error reporting
if (DEBUG_MODE) :
    error_reporting(E_ALL);
endif;

// set the autoloader
spl_autoload_register(array('kpf\system\KPF', 'autoload'));

// set the exception handler
set_exception_handler(array('kpf\sytem\KPF', 'ExceptionHandler'));

// set the error handler
set_error_handler(array('kpf\system\KPF', 'ErrorHandler'));

// define escape string short cut.
function escapeString($string) {
    return kpf\system\KPF::getDB()->escapeString($string);
}
?>