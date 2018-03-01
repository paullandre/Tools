<?php

define("ROOT", __DIR__);
//include_once ROOT . "/model/connection.class.php";
include_once ROOT . "/model/model.class.php";
include_once ROOT . "/entity/files.class.php";

global $argv;

function loopFolder($dir, $type = null, $min = null)
{    
    $data = "";
    $files = scandir($dir);
    $i = 0;
    $gb = array();
    $mb = array();
    $kb = array();
    
    foreach ($files as $key => $val) 
    {        
        if($val == "." || $val == "..")         continue;
        if($val == '$RECYCLE.BIN')              continue;
        if($val == 'System Volume Information') continue;

        if(!(is_dir($dir . $val))) 
        {
//            continue;            
            $fp = fopen($dir . $val, "r");
            $fstat = fstat($fp);         
            $size = formatSizeUnits($fstat['size'], $min, $type);                        
            
            if($size['cat'] == 4)
            {      
                $files2 = new Files();
                $files2->setFileName("$val");
                $files2->setPath("$dir\\");
                $files2->setSize($size['converted']);
                $files2->setUnit("GB");
                $files2->setActualSize($fstat['size']);
                $files2->setFull("$dir\\" . "$val");
                                
                Model::insertRecord($files2);
                    
                $data .= $size['size'] . " - " . $dir . $val . "\n";
//                print $size['size'] . " - " . $dir . $val . "\n";
                print $data;
            }
            else if($size['cat'] == 3)
            {                
                if($size['size'] > $min)
                {
                    $files2 = new Files();
                    $files2->setFileName("$val");
                    $files2->setPath("$dir\\");
                    $files2->setSize($size['converted']);
                    $files2->setUnit("MB");
                    $files2->setActualSize($fstat['size']);
                    $files2->setFull("$dir\\" . "$val");

                    Model::insertRecord($files2);  
                    
                    $data .= $size['size'] . " - " . $dir . $val . "\n";
                    print $size['size'] . " - " . $dir . $val . "\n";                                                   
                }                
            }
            else if($size['cat'] == 2)
            {                
                if($size['size'] > $min)
                {
                    $files2 = new Files();
                    $files2->setFileName("$val");
                    $files2->setPath("$dir\\");
                    $files2->setSize($size['converted']);
                    $files2->setUnit("KB");
                    $files2->setActualSize($fstat['size']);
                    $files2->setFull("$dir\\" . "$val");
                                        
                    Model::insertRecord($files2);
                    
                    $data .= $size['size'] . " - " . $dir . $val . "\n";
                    print $size['size'] . " - " . $dir . $val . "\n";                    
                }                        
            }
            else if($size['cat'] == 1)
            {                
                if($size['size'] > $min)
                {
                    $data .= $size['size'] . " - " . $dir . $val . "\n";
                    print $size['size'] . " - " . $dir . $val . "\n";                    
                }                           
            }

            fclose($fp);                            
        }
        else
        {
            $i++;            
            $current = $dir . $val . "\\";
            
            if(is_dir($current))
            {
                loopFolder($current, $min, $type);
            }            
        }   
    }        
    
    return $data;
}

//print_r(array_slice($fstat, 13));
    
function formatSizeUnits($bytes, $min = null, $type = null)
{    
    if($type == "GB")
    {             
        $arr = number_format($bytes / 1073741824, 2);
        if ($bytes >= 1073741824 && $arr >= $min)
        {            
            $bytes = array("cat" => 4, "converted" => $arr, "size" => number_format($bytes / 1073741824, 2) . ' GB');
        }        
    }
    else if($type == "MB")
    {            
        $arr = number_format($bytes / 1048576, 2);
        
        if ($bytes >= 1048576 && $bytes < 1073741824)
        {
            if($arr >= $min)
            {                
                $bytes = array("cat" => 3, "converted" => $arr, "size" => number_format($bytes / 1048576, 2) . ' MB');
            }            
        }        
    }
    elseif($type == "KB")
    {
        $arr = number_format($bytes / 1024, 2);
        if ($bytes >= 1024 && $bytes < 1048576)
        {
            if($arr >= $min)
            { 
                $bytes = array("cat" => 2, "converted" => $arr,"size" => number_format($bytes / 1024, 2) . ' KB');
            }
        }        
    }
//    else if ($bytes > 1 && $bytes < 1024)
//    {
//        $bytes[] = array("cat" => 1, "size" => $bytes . ' bytes');        
//    }    
    
    return $bytes;
}

$data2 = loopFolder($argv[1], $argv[2], $argv[3]);

//var_dump($data2);
//die;

$message = "Do you want to save this in text file?\nyes or no y or n: \n";
print $message;
flush();

$confirmation = trim(fgets(STDIN));

if($confirmation == "y" || $confirmation == "yes")
{
    $saveMessage = "Please enter Filename: \n";
    print $saveMessage;
    flush();
    
    $confirmation2 = trim(fgets(STDIN));
    $filename = $confirmation2;
    $file = null;
    $folder = __DIR__ . "\\$argv[3]\\";
    
    if($argv[3] == "GB")
    {
        print "entered here in GB \n";
        
        if(!(file_exists($folder)))
        {            
            exec("mkdir $folder");
        }                
        
        $filename .= "-GB.txt";
                
        $file = fopen($folder . $filename, "w");
        fwrite($file, $data2);       
        fclose($file);
        
        print $filename . " has been saved in $folder\n";
    }
    else if($argv[3] == "MB")
    {
        if(!(file_exists($folder)))
        {            
            exec("mkdir $folder");            
        }                
        
        $filename .= "-MB.txt";
                
        $file = fopen($folder . $filename, "w");
        fwrite($file, $data2);
        fclose($file);
        
        print $filename . " has been saved in $folder\n";
    }
    else if($argv[3] == "KB")
    {
        if(!(file_exists($folder)))
        {            
            exec("mkdir $folder");            
        }
        
        $filename .= "-KB.txt";
                
        $file = fopen($folder . $filename, "w");
        fwrite($file, $data2);
        fclose($file);
        
        print $filename . " has been saved in $folder\n";
    }
}
//print formatSizeUnits($fstat['size']);
?>