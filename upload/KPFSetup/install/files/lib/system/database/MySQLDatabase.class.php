<?php
namespace kpf\system\database;

/**
 * This is a implementation of the daatbase support for
 * MySQL Databases.
 * 
 * @author          Felix Jacobi
 * @copyright       2011.-2012 Keyway-Xelonis
 * @license         GNU Lesser General Public License <http://www.gnu.org/licenses/lgpl.html>
 * @package         com.keyway-xelonis.kpf
 * @subpackage      system.database
 * @category        Portal Framework  
 */

class MySQLDatabase extends kpf\system\database\AbstractDatabase {
    /**
     * Creates a new Database Object.
     * 
     * @param           string          $host           SQL database server host adress
     * @param           string          $user           SQL database server username
     * @param           string          $password       SQL database server password
     * @param           string          $name           SQL database name
     * @param           integer         $port           SQL database server port
     */
    
    public function __construct($host, $user, $password, $name, $port) {
        parent::__construct($host, $user, $password, $name, $port);
    }
    
    /**
     * Creates a new database connection.
     * 
     * @see             kpf\system\database\AbstractDatabase::connect()
     */
    
    protected function connect() {
        try {
            $this->pdo = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->databasename, $this->user, $this->password);
            $this->setAttributes();
        } catch (\PDOException $e) {
            throw new kpf\system\database\exception\DatabaseException($this, "Connecting to MySQL-Server '" . $this->host . "' failed.\n" . $e->getMessage);
        }
    }
}
?>