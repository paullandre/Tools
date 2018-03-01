<?php

function iterate($dir) 
{
    global $argv;
    global $folderName;

    $separator = "";
    $title = array();

    $cntGet = count($_GET);

    if ($cntGet > 0) 
    {
        $separator = "<br />";

        foreach ($_GET as $k => $v) 
        {
            $title[] = ucfirst(strtolower($v));
        }
    }

    $argCnt = count($argv);

    if ($argCnt > 1) 
    {
        $separator = "\n";

        for ($x = 1; $x < $argCnt; $x++)
        {
            $title[] = ucfirst(strtolower($argv[$x]));
        }
    }

    $files = scandir($dir);

    if ("" == $title || "" == $files)
        die("No directory or title supplied");

    foreach ($files as $key => $val) 
    {
        ucfirst(strtolower($val));

        if ($val == "." || $val == "..") 
        {
            continue;
        }

        if (is_dir($dir . $val)) 
        {
            $cnt = count($title);

            for ($i = 0; $i < $cnt; $i++) 
            {
                if (strpos($val, $title[$i]) !== false)
                {
                    print $dir . $val . " --- Main folder" . $separator;
                }
            }

            iterate($dir . $val);
        } 
        else
        {
            $cnt = count($title);
            for ($i = 0; $i < $cnt; $i++) 
            {
                if (strpos($val, $title[$i]) !== false) 
                {
                    if (is_dir($dir . "\\" . $val)) 
                    {
                        $current = $dir . "\\" . $val;

                        print $dir . "\\" . $val . " --- Main folder" . $separator;

                        iterate($current);
                    } 
                    else
                    {
                        print $dir . "\\" . $val . $separator;
                    }
                }
            }
        }
    }
}

iterate("D:\\xampp\\htdocs\\");
?>