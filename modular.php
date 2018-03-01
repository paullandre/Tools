<?php

class modular {

    function checkModular($min = 0, $max = 0) {
        global $argv;
        $flag = false;
        $msg = array();
        $f = 0;

        /* Hardcode Values */
        for ($i = $min; $i <= $max; $i++) {
            if ($i % 3 == 0) {
                print "$i is divisible by 3 \n";
            } else if ($i % 5 == 0) {
                print "$i is divisible by 5 \n";
            } else {
                print "$i \n";
            }
        }
    }

}

$modular = new modular();
$modular->checkModular(1, 30);
?>