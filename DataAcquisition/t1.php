<?php
$first = "长海�五彩池�诺日朗瀑布�犀牛海�老虎海�树正群海�火花海�出沟";
$first = json_encode($first);

        //$first = str_replace('\ufffd', "", $first);
        //$first = json_decode($first);
$ar = array($first, $ab, $second);
file_put_contents("D:/test1.txt", var_export($ar, true));
