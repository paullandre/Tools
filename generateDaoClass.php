<?php
/**
 * @Generate DAO class
 * @Args     Host, Username, Password, Table
 * @author   Paul Andre Francisco <erdan08@yahoo.com> 
 * @Date     2014-11-23
 */

define('OPEN_BRACE', "{");
define('CLOSING_BRACE', "}");
define('VAR_SIGN', "$");
define('INDENTION', "    ");
define('NEW_LINE', "\n");
define('THIS', '$this->');
define('_$', "$");
define("SET", "set");
define("GET", "get");
define("CLASS_NAME", "class");
define("PRIVATE_FUNCTION" ,"private function");
define("DESTINATION", __DIR__ . "\\generated_table_classes\\");

class generateDaoClass
{
    private $connect;
    private $host;
    private $database;
    private $username;
    private $password;
    private $table;
    private $type;
    private $field;
    private $phpOpen;
    private $phpClose;
    private $indent;
    private $newLine;
    private $className;
    private $daoCLass;
    private $setterNames = array();
    private $v_globalSetterGetter = array();
    private $openCurl;
    private $closeCurl;
    private $varSign;
    
    function __construct()
    {
        $this->generateDaoClass();
    }

    private function generateDaoClass()
    {
        global $argv;              
        
        $this->host     = isset($argv[1]) ? $argv[1] : "No host name";
        $this->username = isset($argv[2]) ? $argv[2] : "No username";
        $this->password = isset($argv[3]) ? $argv[3] : "No password";
        $this->database = isset($argv[4]) ? $argv[4] : "No database name";
        $this->table    = isset($argv[5]) ? $argv[5] : "No table name";		
        
        //$this->className = strtoupper($this->table);
        
        $this->className = ucfirst(strtolower($this->table));
        
        $this->field = "Field";
        $this->type  = "Type";
        $this->phpOpen = "<?php";
        $this->phpClose = "?>";
        $this->newLine = NEW_LINE;
        $this->indent = INDENTION;
        $this->openCurl = OPEN_BRACE;
        $this->closeCurl = CLOSING_BRACE;
        $this->varSign = VAR_SIGN;
        
        $this->v_globalSetterGetter = $this->f_getSetterGetterGlobal();
    }
    
    private function f_getSetterGetterGlobal()
    {
        try
        {
            gc_enable();
            
            $byte = 10240;
            
            $con = $this->connect();
            $setterList = array();

            $con->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);

            if( null != $con )
            {
                $sql = $con->prepare("DESCRIBE `$this->table`");
                $sql->execute();

                for($i = 0; $row = $sql->fetch(); $i++)
                {
                    $setterList[] = $row;
                }

                $sql->closeCursor();
                
                return $setterList;
            }
            else
            {
                die("Cannot connect to server");
            }
        } catch (Exception $exc) 
        {
            echo $exc->getTraceAsString();
        }
        /*
        finally
        {
            echo("Entered in Finally block \n");
            //CLEAN/CLOSE ALL OBJECTS

            $sql = null;
            $con = null;
            $this->connect = null;
            
            gc_collect_cycles();
            gc_disable();
         
            
            var_dump(memory_get_usage() / $byte);
            var_dump(memory_get_peak_usage() / $byte);
        }
         */
        return false;
    }
    
    private function phpConstruct()
    {
        $www = INDENTION . "function __construct()" . NEW_LINE;
        $www .= INDENTION . OPEN_BRACE . NEW_LINE;
        $www .= INDENTION . INDENTION . VAR_SIGN ."this->" .$this->table . "();";
        $www .= INDENTION . INDENTION . CLOSING_BRACE . NEW_LINE;
    }
    
    private function getFieldName()
    {
        return $this->field;
    }
    
    private function getFieldType()
    {
        return $this->type;
    }
    
    private function connect()
    {   
        $this->connect = new PDO("mysql:host=$this->host;dbname=$this->database", "$this->username", "$this->password");       
        
        if($this->connect)
        {
            return $this->connect;
        }
        else
        {
            die("Not connected");
        }
    }
    
	private function createClassName($className = null)
    {
        $this->cls = "";
        $this->cls .= CLASS_NAME . " " . $className;
        
        return $this->cls;
    }
    
    private function createFunction($method = null, $functionName, $param = null)
    {
        $this->method = $method;
        $this->param = null;
        $this->functionName = $functionName;
        if($param == null)
        {
            $this->param = "()";
        }
        else
        {
            $this->param = "( " . $param . " )";
        }
        
        $this->function = "public function " . $this->method .  $this->functionName . $this->param . " ";
        
        return $this->function;
    }
    
    private function documentHeader()
    {
        date_default_timezone_set('Asia/Manila');
        
        $createDate = date("Y-m-d h:i:s");
        $docs = "/** \n";
        $docs .= "*@class    $this->className \n";
        $docs .= "*@Author   Paul Andre Francisco\n";
        $docs .= "*@Desc     CLASS_DESCRIPTION\n";
        $docs .= "*@Date     $createDate \n";
        $docs .= "*/ \n";
        
        return $docs;
    }
    
    /* GENERATE THE DEFAULT VALUES FOR SETTERS */
    private function constructors()
    {   
        $cons = $this->indent;
        $cons .= "public function $this->className" . '($array)' . NEW_LINE;
        $cons .= INDENTION . OPEN_BRACE . NEW_LINE;
        $cons .= INDENTION . INDENTION . THIS . 'array = $array;' . NEW_LINE . NEW_LINE;
        $cons .= INDENTION . INDENTION . "self::setDefault();" . NEW_LINE;
        $cons .= INDENTION . CLOSING_BRACE . NEW_LINE . INDENTION;
        
        $cons .= NEW_LINE . INDENTION . "private function setDefault() ". OPEN_BRACE . NEW_LINE;
        $cons .= INDENTION . INDENTION . "if(count(" . THIS . "array) == 0 )" . NEW_LINE;
        $cons .= INDENTION . INDENTION . OPEN_BRACE . NEW_LINE;

        $setterNames = $this->v_globalSetterGetter;
        
        for($t = 0; $t < count($setterNames); $t++)
        {
            $fieldName = $setterNames[$t][self::getFieldName()];
            
            $properName = explode("_", $fieldName); 
            
            $count = count($properName);
            $wName = null;
            
            for( $H = 0; $H < $count; $H++ )
            {
                $wName .= ucfirst(strtolower($properName[$H]));
            }
            
            $cons .= $this->indent . $this->indent . $this->indent . "self::set" . $wName . '("");' . $this->newLine;
        }
        $cons .= $this->indent . $this->indent . "}" . $this->newLine;
        $cons .= $this->indent . "}";
        
        return $cons;
    }
    
    private function createSetterGetter()
    {        
        $this->tableObject = $this->v_globalSetterGetter;
        $cnt = count($this->tableObject);
        
        $this->objectFields = "";
        
        for( $i = 0; $i < $cnt; $i++ )
        {
            $fieldName = $this->tableObject[$i][self::getFieldName()];
            $fieldType = $this->tableObject[$i][self::getFieldType()];
            $fieldType = explode("(", $fieldType);
            $variableName = VAR_SIGN .strtolower($fieldName);
            
            $setterName = explode("_", $fieldName);
            $count = count($setterName);
            $wholeName = null;
            
            for( $H = 0; $H < $count; $H++ )
            {
                $wholeName .= ucfirst(strtolower($setterName[$H]));
            }
            
            $this->setterNames[] = $wholeName;
            
            /* COMMENT */
            $this->objectFields .= NEW_LINE . NEW_LINE;
            $this->objectFields .= INDENTION . "/* $fieldType[0] $fieldName */ \n";
            
            /* SETTER */
            //$this->objectFields .= INDENTION . "private function set" . $wholeName . "($variableName) " . OPEN_BRACE . NEW_LINE;
            $this->objectFields .= INDENTION . $this->createFunction(SET, $wholeName, $variableName) . OPEN_BRACE . NEW_LINE;
            $this->objectFields .= INDENTION . INDENTION . THIS ."array = array_merge(" 
                    . THIS . "array, array( '$fieldName' => $variableName));" . NEW_LINE;
            $this->objectFields .= INDENTION . CLOSING_BRACE . NEW_LINE . NEW_LINE;
            
            /* GETTER */
            //$this->objectFields .= INDENTION ."private function get". $wholeName . "()" . OPEN_BRACE . NEW_LINE;
            $this->objectFields .= INDENTION . $this->createFunction(GET,$wholeName, null) . OPEN_BRACE . NEW_LINE;
            $this->objectFields .= INDENTION . INDENTION . "return ". THIS ."array['$fieldName'];" . NEW_LINE;
            $this->objectFields .= INDENTION .  CLOSING_BRACE;
        }
        
        return $this->objectFields;
    }
    
    private function createInsertValues()
    {
        $this->tableObject = $this->v_globalSetterGetter;
        $cnt = count($this->tableObject);
        $this->stringArray = array("char","varchar","tinytext","text","mediumtext","longtext");
        $this->fieldValues = "";
        
        $this->objectFields = "";
        $str = "";
        $opening;
        $closing;
        $concat = VAR_SIGN.'str .= ';
        
        $this->fieldValues .= INDENTION . INDENTION . VAR_SIGN.'str = "";' . NEW_LINE;
        
        $d = '"';
        $s = "'";
        $dot = ".";
        
        for( $i = 0; $i < $cnt; $i++ )
        {
            $fieldName = $this->tableObject[$i][self::getFieldName()];
            $fieldType = $this->tableObject[$i][self::getFieldType()];
            $fieldType = explode("(", $fieldType);
            //echo $fieldName . ': ' . $fieldType . "\n";
            
            $setterName = explode("_", $fieldName);
            $count = count($setterName);
            $wholeName = null;
            $opening = null;
            $closing = null;
            
            for( $H = 0; $H < $count; $H++ )
            {
                $wholeName .= ucfirst(strtolower($setterName[$H])) ;
            }
            
            if( in_array($fieldType[0], $this->stringArray) )
            {
                $opening = $d.$s.$d.$dot;

                if($cnt - $i == 1)
                {
                    $closing = " ".$dot.$d.$s."".$d.";";
                }
                else
                {
                    $closing = " ".$dot.$d.$s.", ".$d.";";
                }
            }
            else
            {
                $opening = "";
                
                if($cnt - $i == 1)
                {
                    $closing = ";";
                }
                else
                {
                    $closing = " " .$dot.$d .", " .$d .";";
                }
            }
            
            $this->fieldValues .= INDENTION . INDENTION . $concat . $opening . "self::get" . $wholeName . "()" . $closing . NEW_LINE;
            
            //$this->setterNames[] = $opening . " self::get" . $wholeName . "()" . $closing;
        }
        $this->fieldValues .= NEW_LINE . INDENTION . INDENTION . "return " . VAR_SIGN.'str;';
        $this->fieldValues .= NEW_LINE . INDENTION . CLOSING_BRACE;
        
        return INDENTION . $this->createFunction("toInsertCSV", null) . OPEN_BRACE . NEW_LINE .  $this->fieldValues;
    }
    
    private function createClass()
    {   
        try 
        {
            $daoClass = fopen(DESTINATION . "$this->className.class.php", "w") or die("Unable to open file!");
            
            $print  = $this->phpOpen . $this->newLine;
            $print .= $this->documentHeader() . $this->newLine;
            
            $print .= $this->createClassName( $this->className );
            
            $print .= $this->newLine . $this->openCurl . $this->newLine;
            $print .= $this->indent . "private " . $this->varSign . "array;";
            $print .= $this->newLine . $this->newLine;
            
            $print .= $this->constructors();
            
            $print .= $this->createSetterGetter();
            
            $print .= $this->newLine . $this->newLine;
            
            $print .= $this->createInsertValues();							
            
            $print .= $this->newLine ."}" . $this->newLine;
			
            $print .= $this->newLine;
			
            $print .= $this->createDaoClass();
			
            $print .= $this->phpClose;
            $print .= $this->newLine;								
            
            fwrite($daoClass, $print);
            fclose($daoClass);
        } catch (Exception $exc) 
        {
            echo $exc->getTraceAsString();
        }
    }
    
    private function createGetRecord( $where_field = null, $dbType )
    {
        $this->getRecord = INDENTION . $this->createDaoFunctions( GET, "Record", array($where_field, $dbType) ) . OPEN_BRACE;    
        $this->getRecord .= NEW_LINE . INDENTION . INDENTION;
        $this->getRecord .= 'global $connMgr;';
        $this->getRecord .= NEW_LINE . INDENTION . INDENTION;
        $this->getRecord .= '$dbCon = $connMgr->getCon( static::$SCHEMA, ' . $dbType . ' );';
        $this->getRecord .= NEW_LINE . INDENTION . INDENTION;
        $this->getRecord .= VAR_SIGN . 'where = "DESIRED_TABLE_ID = ' . $where_field . '";';
        $this->getRecord .= NEW_LINE . INDENTION . INDENTION;
        $this->getRecord .= VAR_SIGN . "record = Collector::getRecord( " . VAR_SIGN . "dbCon, static::" . VAR_SIGN . "TABLENAME, " . VAR_SIGN . "where );";
        $this->getRecord .= NEW_LINE . INDENTION . INDENTION;
        $this->getRecord .= 'if( $record != null )' . OPEN_BRACE;
        $this->getRecord .= NEW_LINE . INDENTION . INDENTION . INDENTION;
        $this->getRecord .= 'return new ' . $this->className . '( $record );';
        $this->getRecord .= NEW_LINE . INDENTION . INDENTION . CLOSING_BRACE;
        $this->getRecord .= NEW_LINE . NEW_LINE . INDENTION . INDENTION;
        $this->getRecord .= "return null;";
        $this->getRecord .= NEW_LINE . INDENTION . CLOSING_BRACE . NEW_LINE;
        //return new Brv_sg_bundle_mst($record);
        return $this->getRecord;
    }
    
    private function createGetList( $dbType )
    {
        $this->getList = INDENTION . $this->createDaoFunctions( GET, 'List', $dbType ) . OPEN_BRACE;
        $this->getList .= NEW_LINE . INDENTION . INDENTION;
        $this->getList .= 'global $connMgr;';
        $this->getList .= NEW_LINE . INDENTION . INDENTION;
        $this->getList .= '$dbCon = $connMgr->getCon( static::$SCHEMA, ' . $dbType . ' );';
        $this->getList .= NEW_LINE . INDENTION . INDENTION;
        $this->getList .= VAR_SIGN . 'where = "";';
        $this->getList .= NEW_LINE . INDENTION . INDENTION;
        $this->getList .= '$list = Collector::getList( $dbCon, static::$TABLENAME, $where );';
        $this->getList .= NEW_LINE . INDENTION . INDENTION;
        $this->getList .= '$results = array();';
        $this->getList .= NEW_LINE . NEW_LINE . INDENTION . INDENTION;
        $this->getList .= 'foreach( $list as $record ) ' . OPEN_BRACE;
        $this->getList .= NEW_LINE . INDENTION . INDENTION . INDENTION;
        $this->getList .= 'array_push( $results, new ' . $this->className . '($record) );';
        $this->getList .= NEW_LINE . INDENTION . INDENTION . CLOSING_BRACE;
        $this->getList .= NEW_LINE . NEW_LINE . INDENTION . INDENTION;
        $this->getList .= 'return $results;';
        $this->getList .= NEW_LINE . INDENTION . CLOSING_BRACE . NEW_LINE;
        
        return $this->getList;
        
        //array_push($results, new Brv_sg_bundle_mst($record));
    }
    
    private function createInsert()
    {
        $this->insert = INDENTION . $this->createDaoFunctions(null, "insert", '$record') . " " . OPEN_BRACE;
        $this->insert .= NEW_LINE . INDENTION . INDENTION;
        $this->insert .= 'try ' . OPEN_BRACE . NEW_LINE . INDENTION . INDENTION . INDENTION;
        $this->insert .= 'global $conMgr;';
        $this->insert .= NEW_LINE . INDENTION . INDENTION . INDENTION;
        $this->insert .= '$dbCon = $connMgr->getCon(static::$SCHEMA, DB_TYPE_MST);';
        $this->insert .= NEW_LINE . NEW_LINE . INDENTION . INDENTION . INDENTION;
        $this->insert .= 'if($record != null) ' . OPEN_BRACE;
        $this->insert .= NEW_LINE . INDENTION . INDENTION . INDENTION . INDENTION;
        $this->insert .= 'Recorder::insert($dbCon, static::$TABLENAME, $record);';
        $this->insert .= NEW_LINE . INDENTION . INDENTION . INDENTION;
        $this->insert .= CLOSING_BRACE;
        $this->insert .= NEW_LINE . INDENTION . INDENTION;
        $this->insert .= CLOSING_BRACE . ' catch (Exception $e) ' . OPEN_BRACE;
        $this->insert .= NEW_LINE . INDENTION . INDENTION . INDENTION;
        $this->insert .= 'throw $e;';
        $this->insert .= NEW_LINE . INDENTION . INDENTION . CLOSING_BRACE;
        $this->insert .= NEW_LINE . INDENTION . CLOSING_BRACE . NEW_LINE;
        //throw $e;
        
        return $this->insert;
    }
    
    private function createDelete()
    {
        $this->delete = INDENTION . $this->createFunction(null, 'delete', '$id_to_delete') . " " . OPEN_BRACE;
        $this->delete .= NEW_LINE . INDENTION . INDENTION;
        $this->delete .= 'try ' . OPEN_BRACE;
        $this->delete .= NEW_LINE . INDENTION . INDENTION . INDENTION;
        $this->delete .= 'global $connMgr;';
        $this->delete .= NEW_LINE . INDENTION . INDENTION . INDENTION;
        $this->delete .= '$dbCon = $connMgr->getCon(static::$SCHEMA, DB_TYPE_MST);';
        $this->delete .= NEW_LINE . NEW_LINE . INDENTION . INDENTION .INDENTION;
        $this->delete .= '$where = "ID_TO_DELETE = ' . "'$" . "id_to_delete'" . '";';
        $this->delete .= NEW_LINE . NEW_LINE . INDENTION . INDENTION .INDENTION;
        $this->delete .= 'Recorder::deleteSelf($dbCon, static::$TABLENAME, $where);';
        $this->delete .= NEW_LINE . INDENTION . INDENTION;
        $this->delete .= CLOSING_BRACE . ' catch(Exception $e) ' . OPEN_BRACE;
        $this->delete .= NEW_LINE . INDENTION . INDENTION .INDENTION;
        $this->delete .= 'throw $e;';
        $this->delete .= NEW_LINE . INDENTION . INDENTION . CLOSING_BRACE;
        $this->delete .= NEW_LINE . INDENTION . CLOSING_BRACE;
        
        return $this->delete;
    }
    
    private function createUpdate()
    {
        $this->update = INDENTION . $this->createFunction(null, 'update', '$record') . " " . OPEN_BRACE;
        $this->update .= NEW_LINE. INDENTION . INDENTION;
        $this->update .= 'try ' . OPEN_BRACE;
        $this->update .= NEW_LINE . INDENTION . INDENTION . INDENTION;
        $this->update .= 'global $conMgr;';
        $this->update .= NEW_LINE . INDENTION . INDENTION . INDENTION;
        $this->update .= '$DESIRED_ID = $record->getDesired_id();';
        $this->update .= NEW_LINE . NEW_LINE . INDENTION . INDENTION . INDENTION;
        $this->update .= 'Recorder::update($dbCon, static::$TABLENAME, $record);';
        $this->update .= NEW_LINE . INDENTION . INDENTION;
        $this->update .= CLOSING_BRACE . ' catch(Exception $e) ' . OPEN_BRACE;
        $this->update .= NEW_LINE . INDENTION . INDENTION . INDENTION;
        $this->update .= 'throw $e;';
        $this->update .= NEW_LINE . INDENTION . INDENTION . CLOSING_BRACE;
        $this->update .= NEW_LINE . INDENTION . CLOSING_BRACE . NEW_LINE;
        
        return $this->update;
    }
    
    private function createDaoClass()
    {
        $this->dao = "";
        
        $this->dao .= $this->createClassName( $this->className . "_DAO" );
        $this->dao .= $this->newLine . $this->openCurl;
        $this->dao .= $this->newLine . $this->indent;
        
        $this->dao .= 'private static $SCHEMA = ' . "'CHANGE_TO_YOUR_SCHEMA';";
        $this->dao .= $this->newLine . $this->newLine . $this->indent;
        
        $this->dao .= 'private static $TABLENAME = ' . "'". strtoupper($this->className) . "';";
        $this->dao .= $this->newLine . $this->newLine;
        
        /* CRUD TRANSACTIONS HERE */
        
        /* public function getRecord() */
        $this->dao .= $this->createGetRecord( '$bundle_id', '$dbType' );
        $this->dao .= $this->newLine;
        
        /* public function getList() */
        $this->dao .= $this->createGetList('$dbType');
        $this->dao .= $this->newLine;
        
        /* public function insert() */
        $this->dao .= $this->createInsert();
        $this->dao .= $this->newLine;
        
        /* public function delete() */
        $this->dao .= $this->createDelete();
        
        $this->dao .= $this->newLine . $this->closeCurl . $this->newLine;
        
        return $this->dao;
    }
    
    private function createDaoFunctions($method = null, $functionName, $param = array())
    {
        $this->method = $method;
        $this->param = null;
        $this->functionName = $functionName;
        if($param == null)
        {
            $this->param = "()";
        }
        else
        {
            if(is_array($param))
            {
                $counter = count($param);
            
                if( $counter > 1 )
                {
                    $this->param = "( "  . implode(", ", $param) . " )" ;
                }
                else
                {
                    $this->param = "( " . $param[$counter - 1] . " )";
                }
            }
            else
            {
                $this->param = "( " . $param . " )";
            }
        }
        
        $this->function = "public function " . $this->method . $this->functionName . $this->param . " ";
        
        return $this->function;
    }
    
    function main()
    {
        //ORIGINAL CODE
        $this->createClass();
        
        //$this->createDaoClass();
        //print $this->createGetList('BUNDLE_ID', '$dbType');
        //print $this->createInsert();
    }
}

$dao = new generateDaoClass();

$dao->main();

?>
