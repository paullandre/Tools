<?php
/** 
*@class    Empty_folders 
*@Author   Paul Andre Francisco
*@Desc     CLASS_DESCRIPTION
*@Date     2017-10-29 12:08:27 
*/ 

class Empty_folders
{
    private $array;

    public function Empty_folders($array)
    {
        $this->array = $array;

        self::setDefault();
    }
    
    private function setDefault() {
        if(count($this->array) == 0 )
        {
            self::setId("");
            self::setFolderName("");
            self::setFullPath("");
            self::setStatus("");
            self::setStatusCode("");
            self::setDeleteDate("");
        }
    }

    /* int id */ 
    public function setId( $id ) {
        $this->array = array_merge($this->array, array( 'id' => $id));
    }

    public function getId() {
        return $this->array['id'];
    }

    /* varchar folder_name */ 
    public function setFolderName( $folder_name ) {
        $this->array = array_merge($this->array, array( 'folder_name' => $folder_name));
    }

    public function getFolderName() {
        return $this->array['folder_name'];
    }

    /* varchar full_path */ 
    public function setFullPath( $full_path ) {
        $this->array = array_merge($this->array, array( 'full_path' => $full_path));
    }

    public function getFullPath() {
        return $this->array['full_path'];
    }

    /* varchar status */ 
    public function setStatus( $status ) {
        $this->array = array_merge($this->array, array( 'status' => $status));
    }

    public function getStatus() {
        return $this->array['status'];
    }

    /* int status_code */ 
    public function setStatusCode( $status_code ) {
        $this->array = array_merge($this->array, array( 'status_code' => $status_code));
    }

    public function getStatusCode() {
        return $this->array['status_code'];
    }

    /* datetime delete_date */ 
    public function setDeleteDate( $delete_date ) {
        $this->array = array_merge($this->array, array( 'delete_date' => $delete_date));
    }

    public function getDeleteDate() {
        return $this->array['delete_date'];
    }

    public function toInsertCSV() {
        $str = "";
        $str .= self::getId() .", ";
        $str .= "'".self::getFolderName() ."', ";
        $str .= "'".self::getFullPath() ."', ";
        $str .= "'".self::getStatus() ."', ";
        $str .= self::getStatusCode() .", ";
        $str .= self::getDeleteDate();

        return $str;
    }
}

class Empty_folders_DAO
{
    private static $SCHEMA = 'CHANGE_TO_YOUR_SCHEMA';

    private static $TABLENAME = 'EMPTY_FOLDERS';

    public function getRecord( $bundle_id, $dbType ) {
        global $connMgr;
        $dbCon = $connMgr->getCon( static::$SCHEMA, $dbType );
        $where = "DESIRED_TABLE_ID = $bundle_id";
        $record = Collector::getRecord( $dbCon, static::$TABLENAME, $where );
        if( $record != null ){
            return new Empty_folders( $record );
        }

        return null;
    }

    public function getList( $dbType ) {
        global $connMgr;
        $dbCon = $connMgr->getCon( static::$SCHEMA, $dbType );
        $where = "";
        $list = Collector::getList( $dbCon, static::$TABLENAME, $where );
        $results = array();

        foreach( $list as $record ) {
            array_push( $results, new Empty_folders($record) );
        }

        return $results;
    }

    public function insert( $record )  {
        try {
            global $conMgr;
            $dbCon = $connMgr->getCon(static::$SCHEMA, DB_TYPE_MST);

            if($record != null) {
                Recorder::insert($dbCon, static::$TABLENAME, $record);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete( $id_to_delete )  {
        try {
            global $connMgr;
            $dbCon = $connMgr->getCon(static::$SCHEMA, DB_TYPE_MST);

            $where = "ID_TO_DELETE = '$id_to_delete'";

            Recorder::deleteSelf($dbCon, static::$TABLENAME, $where);
        } catch(Exception $e) {
            throw $e;
        }
    }
}
?>
