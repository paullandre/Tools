<?php

/**
 * @Description: Main file to choose action
 * @author Paul Andre Francisco
 */

include_once '/model/model.class.php';
include_once '/entity/files.class.php';

class Tools
{
    function Tools() {}
    
    function pickAction($action)
    {        
        $confirmation = $action;
        
        if($confirmation == "Delete")
        {  
            include_once "/cleanup.php";
        }
        else if($confirmation == "Find")
        {
            include_once "/finder.php";    
        }
        else if($confirmation == "Check")
        {            
            include_once "/files.php";
            
            loopFolder("D:\\", 1, "GB");
        }
        else
        {
            die;
        }
    }
}

$message = "Please select action to do: 
            Delete empty folder (Delete)
            Find files/movies (Find)
            Check folder Sizes (Check).\n";
print $message;
flush();

$confirmation = trim(fgets(STDIN));

$tools = new Tools();
$tools->pickAction($confirmation);
        
?>
