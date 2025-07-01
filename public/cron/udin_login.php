<?php
include('./config/config.php');
include('./db_connection/db_connect.php');
$a='1233';
return $a;
/*******  LOGIN **************/
// $bl_login_curl = curl_init();

// curl_setopt_array($bl_login_curl, array(
//     CURLOPT_URL => $bourlog_base_url . 'accounts/users/login',

//     CURLOPT_SSL_VERIFYPEER => false,
//     CURLOPT_SSL_VERIFYHOST => false,
//     CURLOPT_RETURNTRANSFER => true,
//     CURLOPT_ENCODING => '',
//     CURLOPT_MAXREDIRS => 10,
//     CURLOPT_TIMEOUT => 0,
//     CURLOPT_FOLLOWLOCATION => true,
//     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//     CURLOPT_CUSTOMREQUEST => 'POST',
//     CURLOPT_POSTFIELDS => '{
//     "email": "' . $bourlog_email  . '",
//     "password": "' . $bourlog_password  . '"
// }',
//     CURLOPT_HTTPHEADER => array(
//         'Content-Type: application/json'
//     ),
// ));

// $bl_login_response = curl_exec($bl_login_curl);

// curl_close($bl_login_curl);
// $bl_login_data  =   json_decode($bl_login_response, true);
// $bl_token  = $bl_login_data['token'];

/***LOGIN COMPLETE */
