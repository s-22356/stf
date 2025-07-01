<?php

$n  =1;
$arr_1  =   array(
    'name'      =>  'sudip',
    'title'     =>  'maity'
);

if($n  >   0){
    $arr_2  =   array(
        'age'   =>  ''
    );
    $arr_1  =   array_merge($arr_1,$arr_2);
}
print_r($arr_1,$arr_1['age']);
?>