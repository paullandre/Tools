<?php

/**
 * @Description: Insert record for empty folders
 * @For reference
 * 
 * @author Paul Andre Francisco
 */
class emptyFolder 
{
    protected $id, $folderName, $fullPath, $status, $statusCode, $deleteDate;
    private $tableName = "empty_folders";

    public function emptyFolder()
    {
        
    }
    
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }
    public function setFolderName($folderName)
    {
        $this->folderName = $folderName;

        return $this;
    }

    public function getFolderName()
    {
        return $this->folderName;
    }

    public function setFullPath($fullPath)
    {
        $this->fullPath = $fullPath;

        return $this;
    }

    public function getFullPath()
    {
        return $this->fullPath;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }
    
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }
    
    public function getDeleteDate()
    {
        return $this->deleteDate;
    }
    
    public function setUnit($deleteDate)
    {
        $this->deleteDate = $deleteDate;

        return $this;
    }   

    public function getTableName()
    {
        return $this->tableName;
    }

    /* Setup values to be inserted */
    public function insertValues() 
    {        
        $str  = "''" . ",";                
        $str .= "'".    self::getFolderName() . "', ";       
        $str .= "'".    self::getFullPath() . "', ";
        $str .= "'".    self::getStatus() . "', ";
        $str .=         self::getStatusCode() . ", ";              
        $str .= "now()"; 
        
        return $str;
    }
}

?>
