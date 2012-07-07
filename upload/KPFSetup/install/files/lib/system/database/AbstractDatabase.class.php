<?php
namespace kpf\system\database;

/**
 * This is an abstract implementation of a database access class using PDO.
 * 
 * @author          Felix Jacobi <jacobi@keyway-xelonis.com>
 * @copyright       2011-2012 Keyway-Xelonis GmbH
 * @license         GNU Lesser General Public License <http://www.gnu.org/licenses/lgpl.html>
 * @package         com.keyway-xelonis.kpf
 * @subpackage      system.database
 * @category        Portal Framework
 * @abstract
 */

abstract class AbstractDatabase {
    /**
     * Name of the class used for preapred statements.
     * 
     * @var         string 
     */
    
    protected $preparedStatementClassName = 'kpf\system\database\statement\PreparedStatement';
    
    /**
     * Name of the string util class.
     * 
     * @var         string 
     */
    
    protected $stringUtilClassName = 'kpf\util\StringUtil';
    
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
        // set access parameter
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->databasename = $name;
        $this->port = $port;
        
        // creates a new database connection
        $this->connect();
    }

    /**
     * This variable contains the SQL database server hostname.
     *  
     * @var         string
     */
    
    protected $host = '';

    /**
     * This variable contains the SQL database server username.
     * 
     * @var         string 
     */
    
    protected $user = '';

    /**
     * This variable contains the SQL database server password.
     * 
     * @var         string 
     */
    
    protected $password = '';

    /**
     * This variable contains the SQL database name.
     * 
     * @var         string 
     */
    
    protected $databasename = '';
    
    /**
     * This variable contains the SQL database server port.
     * 
     * @var         integer
     */
    
    protected $port = 0;
    
    /**
     * pdo object
     * 
     * @var         object 
     */
    
    protected $pdo = null;
    
    /**
     * Is true if there is an open transaction.
     * 
     * @var         boolean 
     */
    
    protected $hasActiveTransaction = false;
    
    /**
     * Number of excuted queries.
     * 
     * @var         integer 
     */
    
    protected $queryCount = 0;
    
    /**
     * Creates a new database connection.
     * 
     * @return          boolean         true on success
     * @abstract
     */
    
    abstract protected function connect();
    
    /**
     * Initates a transaction.
     * 
     * @return          boolean         true on success 
     */
    
    public function beginTransaction() {
        try {
            if ($this->hasActiveTransaction && $this->pdo == null) :
                return false;
            else :
                $this->hasActiveTransaction = $this->pdo->beginTransaction();
                return $this->hasActiveTransaction;
            endif;
        } catch (\PDOException $e) {
            return false;
        }
    }
    
    /**
     * Commits a transaction.
     * 
     * @return          boolean         true on success
     */
    
    public function commitTransaction() {
        if ($this->hasActiveTransaction && $this->pdo !== null) :
            $this->hasActiveTransaction = false;
            try {
                return $this->pdo->commit();
            } catch (\PDOException $e) {}
        endif;
        return false;
    }
    
    /**
     * Roll backs a transaction.
     * 
     * @return          boolean         true on success  
     */
    
    public function rollBackTransaction() {
        if ($this->hasActiveTransaction && $this->pdo !== null) :
            try {
                return $this->pdo->rollBack();
            } catch (\PDOException $e) {}
        endif;
        return false;
    }
    
    /**
     * Prepares a statement for execution and returns a statement object.
     * 
     * @param           string          $statement
     * @param           integer         $limit
     * @param           integer         $offset 
     */
    
    public function prepareStatement($statement, $limit = 0, $offset = 0) {
        $statement = $this->handleLimitParameter($statement, $limit, $offset);
        try {
            $pdoStatement = $this->pdo->prepare($statement);
            if ($pdoStatement instanceof \PDOStatement) :
                return new $this->preparedStatementClassName($this, $pdoStatement, $statement);
            endif;
        } catch (\PDOException $e) {
            throw new kpf\system\database\exception\DatabaseException("Can not prepare statement '" . $statement . "'.");
        }
    }
    
    /**
     * Handles the limit and offset parameter in SELECT queries.
     * This is the default implementation compatibel to MySQL and PostgreSQL.
     * Other database implentations should override this method. 
     * 
     * @param           string          $query
     * @param           integer         $limit
     * @param           integer         $offset
     * @return          string          $query
     */
    
    public function handleLimitParameter($query, $limit = 0, $offset = 0) {
        if ($limit > 0) :
            $query .= ' LIMIT ' . $limit . ' OFFSET ' . $offset;
        endif;
        
        return $query;
    }
    
    /**
     * Returns the number of the last error.
     * 
     * @return          integer 
     */
    
    public function getErrorNumber() {
        if ($this->pdo !== null) :
            return $this->pdo->errorCode();
        else :
            return 0;
        endif;
    }
    
    /**
     * Returns the description of the last error.
     * 
     * @return          string
     */
    
    public function getErrorDesc() {
        if ($this->pdo !== null) :
            $errorInfoArray = $this->pdo->errorInfo();
            if (isset($errorInfoArray[2])) : 
                return $errorInfoArray[2];
            else :
                return '';
            endif;
        else :
            return '';
        endif;
    }
    
    /**
     * Gets the current database type.
     * 
     * @return          string 
     */
    
    public function getDBType() {
        return get_class($this);
    }
    
    /**
     * Gets the sql version.
     * 
     * @return          string 
     */
    
    public function getVersion() {
        try {
            if ($this->pdo !== null && $this->pdo instanceof \PDO) :
                return $this->pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
            endif;
        } catch (\PDOException $e) {
        }
        return 'unknown';
    }
    
    /**
     * Gets the database name.
     * 
     * @return          string 
     */
    
    public function getDatabaseName() {
        return $this->databasename;
    }
    
    /**
     * Gets the name of the database user.
     * 
     * @return          string
     */
    
    public function getUser() {
        return $this->user;
    }
    
    /**
     * Gets the port of the database host.
     * 
     * @return          integer 
     */
    
    public function getPort() {
        return $this->port;
    }
    
    /**
     * Gets the hostname of the database.
     * 
     * @return          string 
     */
    
    public function getHost() {
        return $this->host;
    }
    
    /**
     * Sets default connection attributes. 
     */
    
    protected function setAttributes() {
        if ($this->pdo !== null) :
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(\PDO::ATTR_CASE, \PDO::CASE_NATURAL);
            $this->pdo->setAttribute(\PDO::ATTR_STRINGIFY_FETCHES, false);
        endif;
    }
    
    /**
     * Gets a instance of the this class or the child.
     * 
     * @return          kpf\system\database\AbstractDatabase
     */
    
    public static function getInstance() {
        $className = get_called_class();
        return new $className;
    }
    
    /**
     * Sends a statement to the database and returns the result.
     * 
     * @param           string          $statement
     * @return          array           on success
     */
    
    public function sendQuery($statement = '') {
        if ($statement !== '') :
            if ($this->pdo !== null) :
                try {
                    $sth = $this->pdo->query($statement);
                    $this->queryCount++;
                    return $sth->fetchAll();
                } catch (\PDOException $e) {}
                return array();
            else :
                return array();
            endif;
        else :
            return array();    
        endif;
    }
    
    /**
     *
     * @param           string          $statement
     * @return          integer 
     */
    
    public function exec($statement = '') {
        if ($statement !== '') :
            if ($this->pdo !== null) :
                try {
                    return $pdo->exec($statement);
                } catch (PDOException $e) {}
            endif;
        endif;
        return 0;
    }
    
    /**
     * Returns the count of the sended querys.
     * 
     * @return          integer
     */
    
    public function getSendedQuerys() {
        return $this->queryCount;
    }
    
    /**
     * Escapes a string for use it in a SQL-Statement.
     * 
     * @param           string          $string
     * @return          string          $string
     */
    
    public function escapeString($string) {
        $stringUtilClass = new $this->stringUtilClassName;
        return $stringUtilClass->addEscapeSlashes($string);
    }
}
?>