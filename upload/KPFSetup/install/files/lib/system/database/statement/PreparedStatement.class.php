<?php
namespace kpf\system\database\statement;

/**
 * This is an implementation of preapred statements based upon pdo statements.
 * 
 * @author          Felix Jacobi <jacobi@keyway-xelonis.com>
 * @copyright       2011-2012 Keyway-Xelonis GmbH
 * @license         GNU Lesser General Public License <http://www.gnu.org/licenses/lgpl.html>
 * @package         com.keyway-xelonis.kpf
 * @subpackage      system.database.statement
 * @category        Portal Framework
 */

class PreparedStatement {
    /**
     * Database object
     * 
     * @var         kpf\system\database\AbstractDatabase 
     */
    
    protected $database = null;
    
    /**
     * pdo statement object.
     * 
     * @var         \PDOStatement 
     */
    
    protected $pdoStatement = null;
    
    /**
     * SQL query
     * 
     * @var         string 
     */
    
    protected $query = '';
    
    /**
     * Creates a new PreparedStatement object.
     * 
     * @param           kpf\system\database\AbstractDatabase            $database
     * @param           \PDOStatement                                   $pdoStatement
     * @param           string                                          $query          SQL query                   
     */
    
    public function __construct(AbstractDatabase $database, \PDOStatement $pdoStatement, $query = '') {
        $this->database = $database;
        $this->pdoStatement = $pdoStatement;
        $this->query = $query;
    }
	
    /**
     * Binds a param to a placeholder.
     *
     * @param		mixed		$placeholder
     * @param  		mixed           $replacement
     * @return		boolean		true on sucess
     */
	 
    public function bindParam($placeholder, $replacement) {
	if ($this->pdoStatement instanceof \PDOStatement) :
            try {
                return $this->pdoStatement->bindParam($placeholder, $replacement);
            } catch (\PDOException $e) {
		return false;
            }
	else :
		return false;
	endif;
    }
    
    /**
     * Binds a column to a php variable.
     * 
     * @param           mixed           $column
     * @param           var             $variable
     * @return          array
     */
    
    public function bindColumn($column, $variable) {
        if ($this->pdoStatement instanceof \PDOStatement) :
            try {
                $this->pdoStatement->bindColumn($column, $variable);
                return $variable;
            } catch(\PDOException $e) {
                return array();
            }
        else :
            return array();
        endif;
    }
     
    /**
      * Execute a prepared statement.
      *
      * @return		boolean		true on success
      */
	
     public function excute() {
	if ($this->pdoStatement instanceof \PDOStatement) :
            try {
		return $this->pdoStatement->execute();
            } catch (\PDOException $e) {
		return false;
            }
	else :
            return false;
	endif;
    }
    
    /**
     * Fetchs the result of a prepared statement.
     * 
     * @param           object          $fetchMode
     * @return          array
     */
    
    public function fetch($fetchMode) {
        if ($this->pdoStatement instanceof \PDOStatement) :
            try {
                return $this->pdoStatement->fetch($fetchMode);
            } catch (\PDOException $e) {
                return array();
            }
        else :
            return array();
        endif;
    }
    
    /**
     * This method is a wrapper of PDOStatement::fetchAll();.
     * 
     * @return          array 
     */
    
    public function fetchAll() {
        if ($this->pdoStatement instanceof \PDOStatement) :
            try {
                return $this->pdoStatement->fetchAll();
            } catch (\PDOException $e) {
                return array();
            }
        else :
            return array();    
        endif;
    }
}
?>