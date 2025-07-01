<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Session;
use DB;
use Exception;
use App;

class PageController extends Controller
{

    public function __construct()
    {
    }




    public function showHome(Request $request  )
    {

        return view('user.pages.public.homepage', [
            'module' => 'page',
            'active-page' => 'home',
        ]);
    }

    public function download_cert()
    {
        return view('user.pages.public.download_cert');
    }

    public function cetificatedownload(Request $request)
    {
        /*dd($request->all());*/
        /*$data = Session::get('x_api_token');
        dd($data);*/

        $now    =   date('Y-m-d H:i:s');
        $today  =   date('Y-m-d');
        $expiry =   date("Y-m-d H:i:s", strtotime('+10 minutes', strtotime($now)));

        $phone      =   $request->username;
        /*dd($phone);*/
        try {
            //check phone number existence
            if (DB::table("tbl_stf_notice35")->where("mobile", $phone)->exists()) {
                //generate otp
                $otp = generateOTP();

                DB::table('tbl_otp')->where('phone', $phone)->delete();
                DB::table('tbl_otp')->insert(
                    [
                        'phone'             => $phone,
                        'otp'               => $otp,
                        'otp_created_on'    => $now,
                        'otp_expired_on'    => $expiry,
                        'otp_count'         => 1
                    ]
                );

                $sms_message = "OTP to login is " . $otp . " for UDIN Portal. DITE GoWB"; //"Your authentication code is: " . $otp;
                initiateSmsActivation($phone, $sms_message, 'AUTH_OTP');

                return response()->json([
                    'error'         =>  false,
                    'message'       =>  'An OTP has been successfully sent to ' . $phone
                ]);
            } else {
                return response()->json(array('error' => true, "message" => "You don't have any certificate."));
            }
        } catch (Exception $e) {
            return response()->json(array('error' => true, "message" => "Unable to process your request."));
        }
    }
    public function validate_otp(Request $request)
    {
        /*dd($request->all());*/

        $now           =    date("Y-m-d H:i:s");
        $phone         =    $request->mobile_no;
        $otp           =    $request->otp_num;

        $user_otp      =      DB::table('tbl_otp')->where('phone', $phone)
            ->where('otp', $otp)->first();
        /*dd($user_otp->otp_created_on);*/
        try{
            if(is_null($user_otp))
            {
                return response()->json([
                    'error'         =>  true,
                    'message'       =>  'Please enter a valid OTP.'
                ]);
            }
            if($now < $user_otp->otp_expired_on){

                $data = DB::table('tbl_stf_notice35')->where('mobile', $phone)->where('uploaded',1)->get();
                /* dd($data);*/
                return response()->json([
                    'error'         =>  false,
                    'data'          =>  $data,
                    'message'       =>  'Here is your certificates.'
                ]);
            }
            else{
                return response()->json([
                    'error'         =>  true,
                    'message'       =>  'Your OTP is expired.'
                ]);
            }
        }catch (Exception $e) {
            return response()->json(array('error' => true, "message" => "Unable to process your request."));
        }
        
    }

    public function downloadUTINcert(Request $request)
    {
        /*dd($request->all());*/
        $udin_no   =    $request->ref;
        /*echo $udin_no;*/
        /*dd(session()->all());*/

        $data  =   DB::table('tbl_stf_notice35')
                    ->where('udin', $udin_no)
                    ->get();


        if($data[0]->udin!=null){

            $post_field_arr  = array(
                'udin'         =>  $udin_no
            );

            $post_field =  http_build_query($post_field_arr);

            $header     =  array(
                'X-Api-Token: ' . Session::get('x_api_token'),
                'Content-Type: application/x-www-form-urlencoded'
            );

            $url    =   config('customparam.udin_base_url') . '/api/verify/udin';
            $x_api_data =   array(
                'method'        =>  'POST',
                'url'           =>  $url,
                'post_field'    =>  $post_field,
                'header'        =>  $header
            );
            $resp   =   curl($x_api_data);
            //print_r($resp);exit();

                if($resp["error"]){

                    $ret_data = array(
                        'error' => true,
                        'status'=> $resp["document"]["status_code"],
                        'status_text'=>$resp["document"]["status_text"],
                        'docName'=> $resp["document"]["document_data"]["doc_name"],
                        'message'=>$resp["message"]
                    );

                    return  $ret_data;
                }else{
                        /*$base_64_certificate  = $resp["document"]["document_data"]["doc_base64"];*/   
                        $base_64_original  = $resp["document"]["document_data"]["doc_original_base64"];

                        $ret_data = array(
                            'error'=>false,
                            'data_original'=>$base_64_original,
                            /*'data_certificate'=>$base_64_certificate,*/
                            'filename'=> $resp["document"]["document_data"]["doc_name"]
                        );

                        return  $ret_data;
                }
        }

    }

}


