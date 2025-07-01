<?php

include('./config/config.php');
include('./db_connection/db_connect.php');
include('./udin_login.php');
$sql  =  "SELECT *  FROM `user_sahajpath_cert`
            WHERE `is_generate` = 1 LIMIT 20";

    $res_sql = $conn->query($sql);
    
    // while($row_res_sql = $res_sql->fetch_assoc()){
    //     /*print_r($row_res_sql["udin"]);*/
        
    // }
