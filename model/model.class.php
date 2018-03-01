<?php
/**
 * @Description: Class of CRUD transaction 
 * @author Paul Andre Francisco
 */

require_once '/connection.class.php';

class Model
{
    private $_connection = null;
    private $_sql = null;
    private $_list = null;
    private $_resource = null;
    private $_operator = null;
    
    public function __construct() 
    { }
    
    public function insertRecord($record = null)
    {      
        $connection = new Connection();
        $resource   = $connection->connect();
        
        if($connection != null)
        {
            $sql  = "INSERT INTO `". $record->getTableName() . "` VALUES(".$record->insertValues().");";                       
            
            $resource->exec($sql);

            return true;
        }
        
        unset($connection);
        unset($resource);
        unset($sql);
        
        return false;
    }
    
    public function displayRecord($table = null, $criteria = null, $value = null, $andWhere = null)
    {
        $this->_connection = new Connection();
        $this->_resource = $this->_connection->connect();
        
        if($this->_connection != null)
        {
            if($criteria == "size" || $criteria == "actual_size")
            {
                $this->_operator = ">=";
            }
            else if($criteria == "unit")
            {
                $this->_operator = "=";
            }
            else
            {
                $this->_operator = "like";
                $value = "%$value%";
            }
            
            $this->_sql = $this->_resource->prepare("SELECT * "
                               . "FROM `" . $table->getTableName(). "` "
                               . "WHERE `$criteria` $this->_operator '$value' "
                               . "$andWhere ");
//            die("SELECT * "
//                               . "FROM `" . $table->getTableName(). "` "
//                               . "WHERE `$criteria` $this->_operator '$value' "
//                               . "$andWhere ");
            
            $this->_sql->execute();                    
            
            $this->_list = $this->_sql->fetchAll();                                   
        }
        
        unset($this->_connection);
        unset($this->_resource);
        unset($this->_sql);
        
        return $this->_list;
    }
    
    public function deleteRecord($table = null, $field = null, $value = null)
    { }
    
    public function updateRecord($table = null, $record = null)
    { }
}

?>
