<?php



if (!function_exists('curl')) {
    function curl($data)
    {
        $now = date('Y-m-d H:i:s');
        $url    =   $data['url'];
        $method =      $data['method'];
        $post_field =    $data['post_field'];
        $header     =   $data['header'];

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => FALSE,
        CURLOPT_SSL_VERIFYHOST=> FALSE,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_POSTFIELDS => $post_field,
        CURLOPT_HTTPHEADER => $header,
        ));

        // Log before executing curl
        Log::info('Curl Request:', [
            'url'           => $url,
            'method'        => $method,
            'post_fields'   => $post_field,
            'headers'       => $header,
            'hit_time'      => date('Y-m-d H:i:s'),
            'reqest_by'     => Session::get('auth_id')? Session::get('auth_id') : '',
        ]);

        $response = curl_exec($curl);

        // Log after executing curl
        Log::info('Curl Response:', [
            'response' => $response,
            'reqest_by'     => Session::get('auth_id')? Session::get('auth_id') : '',
            'response_time' => date('Y-m-d H:i:s'),
            'error' => curl_error($curl),
        ]);

        curl_close($curl);
       
        $data   =   json_decode($response, true);
        
        if(isset($data) && !empty($data) && isset($data['error'])){
            return $data;
        }else{
            return $data['message'];
        }
        
    }
}


if (!function_exists('encryptHEXFormat')) {

    function encryptHEXFormat($data, $key)
        {
            return bin2hex(openssl_encrypt($data, 'aes-256-ecb', $key, OPENSSL_RAW_DATA));
        }
}

if (!function_exists('encryptUDINMicroservice')) {
    function encryptUDINMicroservice($data){
        $url    =   config('customparam.udin_base_url') . '/api/v1/auth/auth/encrypt-data';

        $Tobeencrypted_data   =   $data;

        $post_field_arr  = array(
            'data' => $Tobeencrypted_data
        );
        $post_field = json_encode($post_field_arr);
        $header     = array(
            'Content-Type: application/json'
        );

        $x_api_data = array(
            'method'      => 'POST',
            'url'         => $url,
            'post_field'  => $post_field,
            'header'      => $header
        );

        $data = curl($x_api_data);
        
        if(isset($data) && !empty($data) && isset($data['error'])){
            return $data['data']['encryptedData'];
        }else{
            return $data['message'];
        }
    }
}

if (!function_exists('decryptHEXFormat')) {

    function decryptHEXFormat($data, $key)
    {
        return trim(openssl_decrypt(hex2bin($data), 'aes-256-ecb', $key, OPENSSL_RAW_DATA));
    }

}

if (!function_exists('encryptInfo')) {
    function encryptInfo($data, $ciphering = "AES-128-CBC")
    {
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        $encryption_iv = config('customparam.ENC_IV');
        $encryption_key = openssl_digest(config('customparam.HASH_SALT'), 'MD5', true);
            $encryption = openssl_encrypt(
            $data,
            $ciphering,
            $encryption_key,
            $options,
            $encryption_iv
        );

        return $encryption;
    }
}

if (!function_exists('decryptInfo')) {
    function decryptInfo($data, $ciphering = "AES-128-CBC")
    {
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;

        $decryption_iv = config('customparam.ENC_IV');
        $decryption_key = openssl_digest(config('customparam.HASH_SALT'), 'MD5', TRUE);

        $decryption = openssl_decrypt(
            $data,
            $ciphering,
            $decryption_key,
            $options,
            $decryption_iv
        );

        return $decryption;
    }
}

if (!function_exists('generateUdinXApiToken')) {

    function generateUdinXApiToken()
    {
        $now = date('Y-m-d H:i:s');
        $url    =   config('customparam.udin_base_url') . '/api/v1/auth/auth/generate-auth-token';

        $username   =   config('customparam.udin_username');
        $password   =   config('customparam.udin_password');
        $encKey     =    config('customparam.udin_enckey');



        $post_field_arr  = array(
            'username' => $username,
            'password' => $password,
            'encKey'   => $encKey
        );
        $post_field = json_encode($post_field_arr);
        $header     = array(
            'Content-Type: application/json'
        );
        $x_api_data = array(
            'method'      => 'POST',
            'url'         => $url,
            'post_field'  => $post_field,
            'header'      => $header
        );

        $data = curl($x_api_data);
        return $data;
    }
}

if (!function_exists('generateUdinXApiTokenByRefreshToken')) {

    function generateUdinXApiTokenByRefreshToken()
    {
        $now = date('Y-m-d H:i:s');
        $url    =   config('customparam.udin_base_url') . '/api/v1/auth/auth/refresh-api-token';

        $refresh_token   =   Session::get('refresh_token');

        $post_field_arr  = array(
            'refreshToken' => $refresh_token
        );

        $post_field = json_encode($post_field_arr);
        $header     = array(
            'Content-Type: application/json'
        );
        $x_api_data = array(
            'method'      => 'POST',
            'url'         => $url,
            'post_field'  => $post_field,
            'header'      => $header
        );

        $data = curl($x_api_data);
        return $data;
    }
}

if (!function_exists('generateUdinLoginTokenByRefreshToken')) {
    function generateUdinLoginTokenByRefreshToken(){
        $now = date('Y-m-d H:i:s');
        $url    =   config('customparam.udin_base_url') . '/api/v1/auth/auth/refresh-user-token';

        $refresh_token   =   Session::get('udin_token_refresh_token');

        $post_field_arr  = array(
            'refreshToken' => $refresh_token
        );

        $post_field = json_encode($post_field_arr);
        $header     = array(
            'Content-Type: application/json'
        );
        $x_api_data = array(
            'method'      => 'POST',
            'url'         => $url,
            'post_field'  => $post_field,
            'header'      => $header
        );

        $data = curl($x_api_data);
        return $data;
    }
}