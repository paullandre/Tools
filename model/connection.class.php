<?php

/**
 * @Description: Class for connection to DB
 *
 * @author Paul Andre Francisco
 */
class Connection 
{
    private $_host = null;
    private $_username = null;
    private $_password = null; 
    private $_dbName = null;
    private $_conn = null;
    private $_pdo = null;
            
    function __construct() 
    {        
        $this->_dbName = "files";
        
        $this->_host = "127.0.0.1";
        $this->_username = 'root';
        $this->_password = '';
        $this->_dsn = "mysql:dbname=$this->_dbName;host=$this->_host";
        
        $this->_pdo = new \PDO($this->_dsn, $this->_username, $this->_password);
    }
    
    public function connect()
    {        
        if(null != $this->_pdo)
        {
            return $this->_pdo;
        }
        else
        {
            return "Cannot connect to database.";
        }
    }
    
    public function exec($sql)
    {
        return $this->_pdo->exec($sql);
    }
}

?>
