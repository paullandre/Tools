<?php

class openFile
{
    function openFileName(String $file)
    {
        $fileName = fopen("$file", "r") or die("Unable to open file!");
        $read = fread($fileName,filesize("$file"));       
        
        (int) $x = 0;
        foreach((array)$read as $line => $newLine)
        {
            $x++;
            print $newLine . "$x\n";
            if (ctype_space($newLine)) 
            {
                echo "The string '$newLine' consists of whitespace characters only.\n";
            }
            else 
            {
                echo "The string '$newLine' contains non-whitespace characters.\n";
            }
        }  
        
        fclose($fileName);
    }
}

$openFile = new openFile();
$openFile->openFileName("D:\\xampp\\htdocs\\Resume\\app\Resources\\views\\Pages\\employment.html.twig")
?>