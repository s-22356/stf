<?php


if (!function_exists('app_name')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function app_name()
    {
        return config('customparam.APP_NAME');
        //config('customparam.
    }
}
if (!function_exists('app_short_name')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function app_short_name()
    {
        return config('customparam.APP_SHORT_NAME');
        //config('customparam.
    }
}


if (!function_exists('initiateSmsActivation')) {
    function initiateSmsActivation($phone_number, $message, $template = 'OWNER_REQ', $service_id = null)
    {

        if (is_null($service_id)) {
            $sl_hit_portal = "WEB";
        } else {
            $sl_hit_portal = "API";
        }


        // return true;
        $templateid =   null;

        if ($template == 'OWNER_REQ') {
            $templateid =   '1007560946765778162';
        } else if ($template == 'AUTHORISATION_REQ') {
            $templateid =   '1007171716751141157';
        } else if ($template == 'AUTH_OTP') {
            $templateid =   '1407167144646328905';
        } else if ($template == 'VERIFY_MOBILE') {
            $templateid =   '1407167698826183999';
        } else if ($template == 'CO-SIGNER_REQUEST') {
             $templateid =   '1407167698821550419';
            //  $templateid =   '1407168968092920355';
        } else if ($template == 'AUTH_PERSON_REQUEST') {
            $templateid =   '1407168173970879812';
        }
        $params = array(
            'mobile'    =>  urlencode($phone_number),
            'message'   =>  urlencode($message),
            'templateid' =>  $templateid,
            'passkey'   =>  '@141_UdIn#*#',
            'extra'     =>  ''
        );

        //print_r($params);exit();
        $params_string = "";
        foreach ($params as $key => $value) {
            $params_string .= $key . '=' . $value . '&';
        }
        rtrim($params_string, '&');

        $smsurl     =    'https://sms.nltr.org/send_sms_udin.php';
        //die( credentials()['sms']['nltr']['passkey']) ;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $smsurl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, count($params));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);

        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        //$ipaddress =   get_client_ip();
        $timestamp    =   date('Y-m-d H:i:s');
        $sms_log_data_array = [
            'sl_hit_portal'  => $sl_hit_portal,
            'sl_curl_url'  => $smsurl,
            'sl_curl_params'  => json_encode($params),
            'sl_curl_params_string'  => $params_string,
            'sl_curl_response'  => $response,
            'sl_curl_response_status'  => $status,
            //'sl_hit_ip'  => $ipaddress,
            'sl_template_type'  => $template,
            'sl_timestamp'  => $timestamp,
            'sl_service_id'  => $service_id,
        ];
        //DB::table('tbl_sms_log')->insert($sms_log_data_array);
        if ($status == 200) {
            curl_close($ch);
            return true;
        } else {
            return false;
        }
        //return true;
    }
}

//generate otp
if (!function_exists('generateOTP')) {
    function generateOTP()
    {
        $possible_letters = '1234567890';
        $code = '';
        for ($x = 0; $x < 6; $x++) {
            $code .= ($num = substr($possible_letters, mt_rand(0, strlen($possible_letters) - 1), 1));
        }
        return $code;
    }
}

// Function to get the client IP address
if (!function_exists('get_client_ip')) {
    function get_client_ip()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }
}

//GET APPLICATION NAME
if (!function_exists('app_url')) {
    /**
     * Helper to grab the application url.
     *
     * @return mixed
     */
    function app_url()
    {
        return config('customparam.APP_URL');
        //    return config('app.url');
    }
}

//generate random code
if (!function_exists('generateRandomCode')) {
    function generateRandomCode($length = 6)
    {
        $possible_letters = '23456789BCDFGHJKMNPQRSTVWXYZ';
        $code = '';
        for ($x = 0; $x < $length; $x++) {
            $code .= ($num = substr($possible_letters, mt_rand(0, strlen($possible_letters) - 1), 1));
        }
        return $code;
    }
}


//GENERATE FLASH MESSAGE
if (!function_exists('flash_message')) {
    function flash_message()
    {
        if (Session::has('message')) {
            $sessionMessage = Session::get('message');
            $parts = explode('|', $sessionMessage, 2);
            if (count($parts) === 2) {
                list($type, $message) = $parts;
            } else {
                $type = 'info';
                $message = $sessionMessage;
            }
            if ($type == 'error') {
                $type = 'danger';
            } elseif ($type == 'message') {
                $type = 'info';
            } elseif ($type == 'success') {
                $type = 'success';
            }

            return '<div class="alert alert-' . $type . ' flash-message"><i class="fa fa-times flash-msg-close" aria-hidden="true"></i>
            ' . $message . '</div>';
        }

        return '';
    }
}
