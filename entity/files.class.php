<?php

class Files
{
    protected $id, $fileName, $path, $size, $unit, $actual_size, $full, $date;
    private $tableName = "file_size";

    public function Files()
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
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }
    
    public function getUnit()
    {
        return $this->unit;
    }
    
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    public function getActualSize()
    {
        return $this->actual_size;
    }
    
    public function setActualSize($actual_size)
    {
        $this->actual_size = $actual_size;

        return $this;
    }          
    
    public function getFull()
    {
        return $this->full;
    }

    public function setFull($full)
    {
        $this->full = $full;

        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;

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
        $str .= "'".    self::getFileName() . "', ";       
        $str .=         self::getSize() . ", ";
        $str .= "'".    self::getUnit() . "', ";
        $str .= "'".    self::getPath() . "', ";
        $str .=         self::getActualSize() . ", ";
        $str .= "'".    self::getFull() . "', ";        
        $str .= "now()"; 
        
        return $str;
    }
    
    /* Setup values to update */
    public function updateValues() 
    {        
        
    }
}