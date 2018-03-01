<?php

function compareChanges()
{
    global $argv;
               
    $com  = str_split($argv[1]);
    $com2 = str_split($argv[2]);
    
    $comCnt  = count($com);
    $comCnt2 = count($com2);
    
    $x = 0;

    if($comCnt > $comCnt2)
    {        
        $diff = ((int)$comCnt - (int)$comCnt2);                
        
        print "There is/are $diff deleted letter/s from second string. \n";
                
//        foreach($com as $key => $value)
//        {   
//            foreach($com2 as $k => $v) 
//            {                           
//                if($key == $k)
//                {
//                    print $key . "   " . $k . "\n";
//                    if($value != $v)
//                    {
//                        $x++;                        
//                        $k++;
//                        
//                        print "\n$value -> $v : Unmatched\n";
//                        print "$value has been removed. Index $key \n";
//                        $v = array_push($com2, "V");
//                    }
//                    else
//                    {
//                        print "\n$value -> $v : Matched Index $key\n";
//                    }
//                }                
//            }
//        }
    }        
    
    die;
    
    foreach($com as $key => $value)
    {            
        foreach ($com2 as $k => $v) 
        {
            if($key == $k)
            {                
                if($value != $v)
                {
                    $x++;
                    print "\n$value -> $v : Unmatched\n";
                }
                else
                {
                    print "\n$value -> $v : Matched \n";
                }
            }
        }
    }
    
    if($x > 1)
    {
        print "\nfalse";
    }
    else
    {
        print "\ntrue";
    }
}
        
compareChanges();
?>
