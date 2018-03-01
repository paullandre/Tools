<?php

/** 
 * @Filename: cleanup.php
 * @Class: iterate
 * @Function: Find and delete all empty folders 
 * @Date: 08/29/17
 * @author: Paul Andre Francisco
 */
define("ROOT", __DIR__);
include_once ROOT . "/model/model.class.php";
include_once ROOT . "/entity/emptyFolder.class.php";

print "Searching for empty folders...\n";
sleep(2);

function iterate($dir) 
{    
    $files = scandir($dir);    
    $i = 0;    
    
    foreach ($files as $key => $val) 
    {        
        if($val == "." || $val == "..")         continue;
        if($val == '$RECYCLE.BIN')              continue;
        if($val == 'System Volume Information') continue;
            
        if(!(is_dir($dir . $val))) 
        {
            continue;            
        }
        else
        {            
            $current = $dir . "\\" . $val;
            
            print $current . " is empty. \n";

            $fi = new FilesystemIterator($current);
            $fileCount = iterator_count($fi);
            
            if($fileCount == 0)
            {               
                $message = "Are you sure to delete this folder $current?\nyes or no y or n: ";
                print $message;
                flush();

                $confirmation = trim(fgets(STDIN));
                                 
                if($confirmation == "y" || $confirmation == "yes")
                {            
                    /* Delete folder upon confirmation */
                    rmdir($current);
                    print "Folder deleted \n";
                    
                    $emptyFolder = new emptyFolder();
                    $emptyFolder->setFolderName($val);
                    $emptyFolder->setFullPath($current);
                    $emptyFolder->setStatus("Deleted");
                    $emptyFolder->setStatusCode(1);
                    Model::insertRecord($emptyFolder);
                    
                    continue;
                }
                else if($confirmation == "n" || $confirmation == "no")
                {
                    print "Folder not deleted \n";
                    $emptyFolder = new emptyFolder();
                    $emptyFolder->setFolderName($val);
                    $emptyFolder->setFullPath($current);
                    $emptyFolder->setStatus("Not deleted");
                    $emptyFolder->setStatusCode(0);
                    Model::insertRecord($emptyFolder);
                }                      
                else 
                {
                    print "\nyes, y or n, no and skip only fuckhead";
                }
            }
            
            iterate($current);
        }  
        
        $i++;
    }          
}

iterate("D:\\\\Empty\\");
?>