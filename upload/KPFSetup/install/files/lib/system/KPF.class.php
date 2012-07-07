<?php
namespace kpf\system;

// try to disable the execution time limit
@set_time_limit(0);

// define current kpf version
define('KPF_VERSION', '1.0.0 Alpha 1 (Black Lightning)');

// define current unix time stamp
define('TIME_NOW', time());

// kpf imports
if (!defined('NO_IMPORTS')) :
    require_once KPF_DIR . '/lib/core.functions.php';
endif;

/**
 * KPF is the central class of the portal framework. It holds
 * the access to the module, database and language engine.
 * 
 * @author          Felix Jacobi
 * @copyright       2011-2012 Keyway-Xelonis
 * @license         GNU Lesser General Public License <http://www.gnu.org/licenses/lgpl.html>
 * @package         com.keyway-xelonis.kpf
 * @subpackage      system
 * @category        Portal Framework
 */

class KPF {
    /**
     * Gets a instance of this class or the child class.
     * 
     * @return          kpf\system\KPF
     */
    
    public static function getInstance() {
        $className = get_called_class();
        return new $className;
    }
    
    /**
     * Adds a autoload directory.
     * 
     * @param           string          $autoloadDirectoryIndex
     * @param           string          $autoloadDirectoryValue 
     */
    
    public static function autoloadDirectoryAdd($autoloadDirectoryIndex, $autoloadDirectoryValue) {
        if (!isset(self::$autoloadDirectories[$autoloadDirectoryIndex]) && $autoloadDirectoryValue) :
            self::$autoloadDirectories[$autoloadDirectoryIndex] = $autoloadDirectoryValue;
        endif;
    }
    
    /**
     * List auf autoload directorys.
     * 
     * @var         array 
     */
    
    protected static $autoloadDirectories = array();
    
    /**
     * database object
     *
     * @var         kpf\system\database\AvstractDatabase         
     */
    
    protected $databaseObj = null;
    
    /**
     * This function is the constructor of the kpf class.
     * It start the initialization of the class. 
     */
    
    public function __construct() {
        // add autoload directory
        self::$autoloadDirectories['kpf'] = KPF_DIR . '/lib';
        
        // define tmp directory, if is not defined from the user application
        if (!defined('TMP')) :
            define('TMP_DIR', kpf\util\FileUtil::getTempFolder());
        endif;
        
        // start initialization
        $this->initClass();
    }
    
    /**
     * This function is the destructor of the kpf class, it make a flush output. 
     */
    
    public function __destruct() {
        // flush output
        if (ob_get_length() && ini_get('output_handler')) :
            ob_flush();
        else :
            flush();
        endif;
    }
    
    /**
     * This function is the autoloader for the portal framework.
     * It include files by namespace.
     * 
     * @param           string          $className
     */
    
    public static function autoload($className) {
        $namespaces = explode('\\', $className);
        if (count($namespaces) > 1) :
            $applicationPrefix = array_shift($namespaces);
            $loadFile = self::$autoloadDirectories[$applicationPrefix];
            
            foreach ($namespaces as $dirName) :
                $loadFile .= $dirName . '/';
            endforeach;
            
            // add file extension
            $loadFile .= '.class.php';
            
            if (file_exist($loadFile)) :
                require_once $loadFile;
            endif;
        endif;
    }
    
    /**
     * Calls the method from SystemException for show an exception message.
     * 
     * @see             set_exception_handler()
     * @param           array           $e
     */
    
    public static function ExceptionHandler(array $e) {
       throw new kpf\system\exception\SystemException($e); 
    }
    
    /**
     * Calls the method from SystemError for show an error message.
     * 
     * @see             set_error_handler()
     * @param           integer         $errornumber
     * @param           string          $errordesc
     * @param           string          $errorfile
     * @param           string          $errorline
     */
    
    public static function ErrorHandler($errornumber, $errordesc, $errorfile, $errorline) {
        try {
            throw new kpf\system\error\SystemError($errornumber, $errordesc, $errorfile, $errorline);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /* Init functions */
    
    /**
     * This function calls all init functions of the KPF class. 
     */
    
    protected function initClass() {
        // call the initDB method
        $this->initDB();
    }
    
    /**
     * This function initalize the database. 
     */
    
    protected function initDB() {
        // check the type of the database
        if (DB_ENGINE == 'MySQL') :
            self::$databaseObj = new kpf\system\database\MySQLDatabase(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
        elseif (DB_ENGINE == 'PgSQL') :
            self::$databaseObj = new kpf\system\database\PostgreSQLDatabase(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
        elseif (DB_ENGINE == 'SQLite') :
            self::$databaseObj = new kpf\system\database\SQLiteDatabase(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
        elseif (DB_ENGINE == 'MsSQL') :
            self::$databaseObj = new kpf\system\database\MicrosoftSQLDatabase(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
        else :
            throw new kpf\system\exception\SystemException('Your Database Engine <b>' . DB_ENGINE . '</b> is not supported from the Portal Framework!');
        endif;
    }
    
    /* Access functions */
    
    /**
     * Returns the database object
     * 
     * @static
     * @return          object          $databaseObj 
     */
    
    public static final function getDB() {
        return self::$databaseObj->getInstance();
    }
}
?>