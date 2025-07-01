<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;
use Validator;
use Session;


class AdminPostController extends Controller
{

    public function __construct()
    {
        if(Session::has("refresh_token_expired_code") && Session::get("refresh_token_expired_code") == "STFREXP001"){
            
        }
    }


    public function generate_login_phone_otp(Request $request)
    {
        $username   =   config('customparam.udin_username');
        $password   =   config('customparam.udin_password');
        $encKey     =   config('customparam.udin_enckey');
        $phone      =   $request->udin_phone; //69d66e13973144835844a0446c90d5e1
        
        if(DB::table("tbl_stf_auth_prsn_req")
        ->where("stf_apr_ph_no",  encryptInfo($phone))
        ->where(function($query) {
            $query->where('is_delete', '1')
                  ->orWhere('is_delete', '0');
        })
        ->where("is_active","0")
        ->exists()){

            $validation =array(
                "error"     =>  true,
                "message"   =>  "Either Profile was blocked or deleted. Please contact the administrator."
            );
            return response()->json($validation, 200);
        }

        $post_field_arr  = array(
            'userName' => $username,
            'password' => $password,
            'encKey'   => $encKey,
            'phone' =>  encryptUDINMicroservice($phone)
        );
        
        $post_field =  json_encode($post_field_arr);
       
        $header     =  array(
            'X-Api-Token: ' . Session::get('x_api_token'),
            'Content-Type: application/json'
        );

        $url    =   config('customparam.udin_base_url') . '/api/v1/auth/service-auth-person/login/generate-otp';
        $x_api_data =   array(
            'method'        =>  'POST',
            'url'           =>  $url,
            'post_field'    =>  $post_field,
            'header'        =>  $header
        );
        $data   =   curl($x_api_data);
        
        $response = [
            "error" => $data['error'],
            "message" => $data['message'] /* "OTP has been sent to your registered mobile number." */,
        ];

        return response()->json($response, 200);
    }


    public function verify_login_phone_otp(Request $request)
    {
        $phone      =   $request->udin_phone; //69d66e13973144835844a0446c90d5e1
        $udin_otp   =   $request->udin_otp; //69d66e13973144835844a0446c90d5e1
        $encrypt_phone  =  encryptInfo($phone);

        $post_field_arr  = array(
            'phone'    =>  encryptUDINMicroservice($phone),
            'otp'      =>  $udin_otp,
        );

        $post_field =  json_encode($post_field_arr);
     
        $header     =  array(
            'X-Api-Token: ' . Session::get('x_api_token'),
            'Content-Type: application/json'
        );

        $url    =   config('customparam.udin_base_url') . '/api/v1/auth/service-auth-person/login/verify-otp';
        $x_api_data =   array(
            'method'        =>  'POST',
            'url'           =>  $url,
            'post_field'    =>  $post_field,
            'header'        =>  $header
        );
        $data   =   curl($x_api_data);
       
        if (!$data['error']) {
            $now    =    date('Y-m-d H:i:s');
            Session::put('name', $data['data']['userName']);
            Session::put('udin_photo_base64', '');
            Session::put('udin_token', $data['data']['accessToken']);
            Session::put('udin_token_expiry', date('Y-m-d H:i:s', strtotime($now . ' +50 minutes')));
            Session::put('udin_token_refresh_token', $data['data']['refreshToken']);
            Session::put('udin_token_refresh_token_expiry', date('Y-m-d H:i:s', strtotime($now . ' +3 hours 30 minutes')));

            $auth_exists    =   DB::table("tbl_stf_auth_prsn")
            ->where("stf_auth_phone", $encrypt_phone)
            ->where("stf_auth_name", $data['data']['userName'])
            ->where("is_active", 1)
            ->first();
            
            if($auth_exists !=  null){

                DB::table("tbl_stf_auth_prsn")
                ->where("stf_auth_id",  $auth_exists->stf_auth_id)
                ->update([
                    "last_login"                =>  $now,
                    "stf_is_aadhar_verified"    =>  0
                ]);
                Session::put('auth_id', $auth_exists->stf_auth_id);
                Session::put('auth_type', $auth_exists->stf_auth_type);
                
            }else{
                DB::table("tbl_stf_auth_prsn")
                ->insert([
                    "stf_auth_name"     =>  $data['data']['userName'],
                    "stf_auth_phone"    =>  $encrypt_phone,
                    "is_active"         =>  1,
                    "last_login"        =>  $now
                ]);
                $auth_id    =   DB::getPdo()->lastInsertId();
                Session::put('auth_id', $auth_id);
                Session::put('auth_type', "auth_user");
            }
        }
        $response = [
            "error" => $data['error'],
            "message" => $data['message'] /* "OTP has been sent to your registered mobile number." */,
        ];

        return response()->json($response, 200);
    }


  /* ------------------- Updated upload_certificate_excel------------------- */

    public function upload_certificate_excel(Request $request)
    {
        // try {
            $validator = Validator::make($request->all(), [
                'notice_id' => 'required',
                'fir_no' => 'required',
                'us_no' => 'required',
                'police_stn' => 'required',
                'diary_no' => 'required',
                'city' => 'required',
                'accused_name' => 'required',
                'accused_address' => 'required',
                'social_ph_mobile' => 'required',
                'officer_designation' => 'required',
                'officer_place_of_posting' => 'required',
                'officer_phone' => 'required',
                'fir_date' => 'required',
                'diary_date' => 'required'
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                return back()->with('message', [
                    'error' => true,
                    'message' => implode(',', $errors)
                ]);
            }

            $notice_id = $request->notice_id;
            $auth_id = Session::get('auth_id');
            $dates = [
                "fir_date" => $request->fir_date,
                "accused_appearing_date" => $notice_id != '2' ? $request->accused_appearing_date : '',
                "diary_date" => $request->diary_date
            ];

            foreach ($dates as $key => $date) {
                $$key = date('d-m-y', strtotime($date));
            }

            $notice_id_data = DB::table("tbl_stf_notice_dtls")->where("id", $notice_id)->where("is_active", "1")->first();

            if ($notice_id_data->nd_notice_id != 'Notice_67' && (!$request->appearing_time || !$request->accused_appearing_place || !$request->accused_appearing_date)) {
                return back()->with('message', 'error|Please Fill Accused Appearance Details');
            }
            // $officer_place_of_posting = $request->officer_place_of_posting;
            // $officer_place_of_posting = str_replace([', ', ','], "\n", $officer_place_of_posting);
            // dd($officer_place_of_posting);
            $fir_data = [
                "stf_notice_id" => $notice_id_data->nd_notice_id,
                "stf_case_fir_id" => $request->fir_no,
                "stf_us" => $request->us_no,
                "stf_fir_date" => $fir_date,
                "stf_ps" => $request->police_stn,
                "stf_diary_no" => $request->diary_no,
                "stf_diary_date" => $diary_date,
                "stf_city" => $request->city,
                "stf_officer_designation" => $request->officer_designation,
                "stf_place_of_posting" => $request->officer_place_of_posting,
                "stf_officer_ph_no" => $request->officer_phone,
                "stf_fillup_by" => $auth_id
            ];

            if (in_array($notice_id, ["3", "4"])) {
                $fir_data = array_merge($fir_data, [
                    'stf_complinant_name' => $request->complinant_name,
                    'stf_complinant_father_name' => $request->complinant_father_name,
                    'stf_complinant_address' => $request->complinant_address
                ]);
            }

            if (!empty($request->document_name[0]) && $request->document_name[0] != null) {
                $documents = $request->document_name;
                $doc_dtls_94 = [];
                foreach ($documents as $i => $doc) {
                    $doc_dtls_94['stf_notice94_document' . ($i + 1)] = $doc;
                }
                $fir_data['stf_notice_doc_dtls'] = json_encode($doc_dtls_94);
            }
            // dd($request->all(),$fir_data,$request->document_name[0]);
            DB::table("tbl_stf_fir_dtls")->insert($fir_data);
            $stf_fir_id = DB::getPdo()->lastInsertId();

            $accused_dtls = [
                'acc_notice_id' => $notice_id_data->nd_notice_id,
                'acc_fir_id' => $stf_fir_id,
                'acc_name' => $request->accused_name,
                'acc_last_address' => $request->accused_address,
                'acc_ph_mail' => $request->social_ph_mobile,
                'acc_appear_date' => $notice_id_data->nd_notice_id != 'Notice_67' ? date('d-m-y', strtotime($request->accused_appearing_date)) : null,
                'acc_appear_place' => $notice_id_data->nd_notice_id != 'Notice_67' ? $request->accused_appearing_place : null,
                'acc_appear_time' => $notice_id_data->nd_notice_id != 'Notice_67' && !empty($request->appearing_time) ? date('h:i A', strtotime($request->appearing_time)) : null
            ];

            DB::table("tbl_stf_accused_dtls")->insert($accused_dtls);

            $stf_fir_acc_keywords = $accused_dtls;
            $existing_meta_data = DB::table("tbl_stf_fir_dtls")->where('id', $stf_fir_id)->value('stf_extra_data');
            $updated_meta_data = $existing_meta_data ? array_merge(json_decode($existing_meta_data, true), $stf_fir_acc_keywords) : $stf_fir_acc_keywords;
            DB::table("tbl_stf_fir_dtls")->where('id', $stf_fir_id)->update(['stf_extra_data' => json_encode($updated_meta_data)]);

        // } catch (Exception $e) {
        //     return back()->with('message', 'error|Form Submission Failed');
        // }
        return back()->with('message', 'success|Successfully Form Submitted');
    }

    /* ------------------- End upload_certificate_excel------------------- */

    // public function upload_certificate_excel(Request $request){

    //     // dd($request->all());
    //     try {

    //         $validator = Validator::make($request->all(), [
    //             'notice_id' => 'required',
    //             'fir_no' => 'required',
    //             'us_no' => 'required',
    //             'police_stn' => 'required',
    //             'diary_no' => 'required',
    //             'city' => 'required',
    //             'accused_name' => 'required',
    //             'accused_address' => 'required',
    //             'social_ph_mobile' => 'required',
    //             // 'receiver_name' => 'required',
    //             // 'receiver_place' => 'required',
    //             'officer_designation' => 'required',
    //             'officer_place_of_posting' => 'required',
    //             'officer_phone' => 'required',
    //             'fir_date' => 'required',
    //             // 'receiving_date' => 'required',
    //             'diary_date' => 'required'
    //         ]);
            

    
    //         if ($validator->fails()) {
    //             $error    =    json_decode(json_encode($validator->errors()), true);
    //             $msg    =    array();
    //             foreach ($error as $err) {
    
    //                 $msg[]    =    implode(',', array_values($err));
    //             }
    
    //             return back()->with('message', [
    //                 'error' => true, 
    //                 'message' => implode(',', array_values($msg))
    //             ]);
                
    //         }

    //         $notice_id                  =   $request->notice_id;
    //         $fir_no                     =   $request->fir_no;
    //         $us_no                      =   $request->us_no;
    //         $police_stn                 =   $request->police_stn;
    //         $diary_no                   =   $request->diary_no;
    //         $city                       =   $request->city;
    //         $accused_name               =   $request->accused_name;
    //         $accused_address            =   $request->accused_address;
    //         $social_ph_mobile           =   $request->social_ph_mobile;
    //         $appearing_time             =   $notice_id  != '2'?$request->appearing_time:'';
    //         $accused_appearing_place    =   $notice_id  != '2'?$request->accused_appearing_place:'';
    //         /*$receiver_name              =   $request->receiver_name;
    //         $receiver_place             =   $request->receiver_place;*/
    //         $officer_designation        =   $request->officer_designation;
    //         $officer_place_of_posting   =   $request->officer_place_of_posting;
    //         $officer_phone              =   $request->officer_phone;
    //         $auth_id                    =   Session::get('auth_id');
            
    //         $dates = [
    //             "fir_date"                =>  $request->fir_date, 
    //             "accused_appearing_date"  =>  $notice_id  != '2'?$request->accused_appearing_date:'', 
    //             /*"receiving_date"          =>  $request->receiving_date,*/
    //             "diary_date"              =>  $request->diary_date
    //         ];

    //         $fir_date = null;
    //         $accused_appearing_date = null;
    //         /*$receiving_date = null;*/
    //         $diary_date     = null;
            
    //         // Convert and store dates in corresponding variables
    //         foreach ($dates as $key => $date) {
    //             $$key = date('d-m-y', strtotime($date)); // Create a variable dynamically
    //         }

    //         $notice_id_data    =   DB::table("tbl_stf_notice_dtls")->where("id", $notice_id)->where("is_active","1")->first();

    //         if($appearing_time  ==  null  ||  $accused_appearing_place    ==  null  ||  $accused_appearing_date ==  null){
    //             if($notice_id_data->nd_notice_id   !=  'Notice_67'){
    //                 return back()->with('message', 'error|Please Fill Accused Appearence Details');
    //             }
    //         }
            
    //         if($notice_id_data->nd_notice_id    != null){

    //             $fir_data =   array(
    //                 "stf_notice_id"             =>  $notice_id_data->nd_notice_id,
    //                 "stf_case_fir_id"           =>  $fir_no,
    //                 "stf_us"                    =>  $us_no,
    //                 "stf_fir_date"              =>  $fir_date,
    //                 "stf_ps"                    =>  $police_stn,
    //                 "stf_diary_no"              =>  $diary_no,
    //                 "stf_diary_date"            =>  $diary_date,
    //                 "stf_city"                  =>  $city,
    //                 /*"stf_receiver_name"         =>  $receiver_name,
    //                 "stf_receiving_date"        =>  $receiving_date,
    //                 "stf_receiving_place"       =>  $receiver_place,*/
    //                 "stf_officer_designation"   =>  $officer_designation,
    //                 "stf_place_of_posting"      =>  $officer_place_of_posting,
    //                 "stf_officer_ph_no"         =>  $officer_phone,
    //                 "stf_fillup_by"                 =>  $auth_id
    //             );
                
    //             if($notice_id   ==  "3" || $notice_id   ==  "4"){
    //                 $complinant_name        =   $request->complinant_name;
    //                 $complinant_father_name =   $request->complinant_father_name;
    //                 $complinant_address     =   $request->complinant_address;
                    
    //                 $notice_94_dtls =   array(
    //                     'stf_complinant_name'           =>  $complinant_name,
    //                     'stf_complinant_father_name'    =>  $complinant_father_name,
    //                     'stf_complinant_address'        =>  $complinant_address
    //                 );
    //                 if($request->document_name  !=  null){
    //                     $documents = $request->document_name;
    //                     $i = 1;
        
    //                     $doc_dtls_94 = [];
    //                     for ($i = 0; $i < count($documents); $i++) {
    //                         $doc_dtls_94['stf_notice94_document' . ($i + 1)] = $documents[$i];
    //                     }

    //                     $fir_data = array_merge($fir_data, $notice_94_dtls);
    //                     $fir_data['stf_notice_doc_dtls'] = json_encode($doc_dtls_94);
    //                 }else{
    //                     $fir_data   =   array_merge($fir_data, $notice_94_dtls);
    //                 }
                    
    //             }else{

    //                 if($request->document_name  !=  null){
    //                     $documents = $request->document_name;
    //                     $i = 1;
        
    //                     $doc_dtls_94 = [];
    //                     for ($i = 0; $i < count($documents); $i++) {
    //                         $doc_dtls_94['stf_notice94_document' . ($i + 1)] = $documents[$i];
    //                     }
    //                     $fir_data['stf_notice_doc_dtls'] = json_encode($doc_dtls_94);
    //                 }
                    
    //             }
    //             // dd($fir_data);
    //             DB::table("tbl_stf_fir_dtls")->insert($fir_data);
    //             $stf_fir_id     =   DB::getPdo()->lastInsertId();
                
    //             $accused_dtls   =   array(
    //                 'acc_notice_id'     =>  $notice_id_data->nd_notice_id,
    //                 'acc_fir_id'        =>  $stf_fir_id,
    //                 'acc_name'          =>  $accused_name,
    //                 'acc_last_address'  =>  $accused_address,
    //                 'acc_ph_mail'       =>  $social_ph_mobile,
    //                 'acc_appear_date'   =>  $notice_id_data->nd_notice_id != 'Notice_67'? $accused_appearing_date : null,
    //                 'acc_appear_place'  =>  $notice_id_data->nd_notice_id != 'Notice_67'? $appearing_time : null,
    //                 'acc_appear_time'   =>  $notice_id_data->nd_notice_id != 'Notice_67'? $accused_appearing_place :null
    //             );

    //             DB::table("tbl_stf_accused_dtls")->insert($accused_dtls);
    //             $stf_acc_id     =   DB::getPdo()->lastInsertId();

    //             $stf_fir_acc_keywords   =   $accused_dtls;
    //             if (DB::table("tbl_stf_fir_dtls")
    //             ->where('id', $stf_fir_id)
    //             ->whereNotNull('stf_extra_data')
    //             ->exists()) {
    //                 $existing_meta_data = DB::table("tbl_stf_fir_dtls")
    //                     ->where('id', $stf_fir_id)
    //                     ->first('stf_extra_data');

    //                 $updated_meta_data = json_decode($existing_meta_data->stf_extra_data, true);
    //                 $updated_meta_data = array_merge($updated_meta_data, $stf_fir_acc_keywords);
    //                 $updated_meta_data = json_encode($updated_meta_data);

    //                 DB::table("tbl_stf_fir_dtls")
    //                     ->where('id', $stf_fir_id)
    //                     ->update(['stf_extra_data'  => $updated_meta_data]);
    //             } else {
    //                 DB::table("tbl_stf_fir_dtls")
    //                     ->where('id', $stf_fir_id)
    //                     ->update(['stf_extra_data'  => json_encode($stf_fir_acc_keywords)]);
    //             }
                
    //         }
            
    //     } catch (Exception $e) {

    //         $error_code = $e->getMessage();
    //         return back()->with('message', 'error|Form Submission Failed');
    //     }
    //     return back()->with('message', 'success|Successfully Form Submitted');
    // }

    public function generate_aadhar_otp(Request $request)
    {
        $token  =   Session::get('udin_token');
        $aadhar_no  =  $request->udin_aadhaar;

        $post_field_arr  = array(
            'aadhaar_no'    =>  encryptUDINMicroservice($aadhar_no)
        );

        $post_field =  json_encode($post_field_arr);
        $header     =  array(
            'x-api-token:' . Session::get('x_api_token'),
            'x-auth-token:' . Session::get('udin_token'),
            'Content-Type: application/json'
        );

        $url    =   config('customparam.udin_base_url') . '/api/v1/document/upload/request-aadhaar-otp';
        $x_api_data =   array(
            'method'        =>  'POST',
            'url'           =>  $url,
            'post_field'    =>  $post_field,
            'header'        =>  $header
        );
        $data   =   curl($x_api_data);
        
        $response = [
            "error"         => $data['error'],
            "message"       => $data['message'] /* "OTP has been sent to your registered mobile number." */,
            "transaction"   => isset($data['data']['transId']) ? $data['data']['transId'] : '',
            "aadhar_no"     => encryptUDINMicroservice($aadhar_no)
        ];
        return response()->json($response, 200);
    }


    public function verify_aadhar_otp(Request $request)
    {
        $token      =       Session::get('udin_token');

        $transaction_id  =  $request->transaction_id;
        $addhar_no       =  $request->addhar_no;
        $otp_num         =  $request->addhar_otp;
        
        $post_field_arr  = array(
            'trans_id'      =>  $transaction_id,
            'aadhaar_num'   =>  $addhar_no,
            'otp_num'       =>  $otp_num,
            'upload_type'   =>  'utin'
        );

        $post_field =  json_encode($post_field_arr);
        $header     =  array(
            'x-api-token:' . Session::get('x_api_token'),
            'x-auth-token:' . Session::get('udin_token'),
            'Content-Type: application/json'
        );

        $url    =   config('customparam.udin_base_url') . '/api/v1/document/upload/validate-aadhaar-otp';
        $x_api_data =   array(
            'method'        =>  'POST',
            'url'           =>  $url,
            'post_field'    =>  $post_field,
            'header'        =>  $header
        );
        $data   =   curl($x_api_data);
        // dd($data);
        if (!$data['error']) {
            if ($data['code'] == "SUC_DOC_UPL_00002") {
                Session::put('udin_session_aadhar_verifyed', true);
                Session::put('udin_photo_base64', '');
                $auth_id    =   Session::get('auth_id');
                DB::table("tbl_stf_auth_prsn")
                ->where("stf_auth_id", $auth_id)
                ->update([
                    "stf_is_aadhar_verified"    =>  1,
                ]);
            }
        }
        $response = [
            "error"         => $data['error'],
            "message"       => $data['message'] /* "OTP has been sent to your registered mobile number." */,
            //  "transaction"   =>  isset( $data['trans_id'])? $data['trans_id'] : ''
        ];
        return response()->json($response, 200);
    }

    public function generate_udin_certificate(Request $request)
    {
        $token      =   Session::get('udin_token');
        $auth_id    =   Session::get('auth_id');
        $id         =   $request->id;

        $cert_data    =   DB::table('tbl_stf_fir_dtls')
            ->join('tbl_stf_notice_dtls', 'tbl_stf_fir_dtls.stf_notice_id', '=', 'tbl_stf_notice_dtls.nd_notice_id')
            ->where('tbl_stf_fir_dtls.uploaded', 0)
            ->where('tbl_stf_fir_dtls.id', $id)
            ->where('stf_fillup_by', $auth_id)
            ->limit(1)
            ->get();
        
        $template_id    =   null;
        if($cert_data[0]->stf_notice_id    ==  "Notice_35"){
            $template_id    =   config('customparam.stf_notice35_temp_id');
        }elseif($cert_data[0]->stf_notice_id    ==  "Notice_67"){
            $template_id    =   config('customparam.stf_notice67_temp_id');
        }elseif($cert_data[0]->stf_notice_id    ==  "Notice_94"){
            $template_id    =   config('customparam.stf_notice94_temp_id');
        }else{
            $template_id    =   config('customparam.stf_notice179_temp_id');
        }

        foreach ($cert_data[0]  as $cd) {
            //generate utin rate
            $post_field_arr  = array(
                'docValidity'  =>  10,
                'templateId'   =>  $template_id
            );

            $post_field =  json_encode($post_field_arr);
            $header     =  array(
                'x-auth-token: ' . $token,
                'x-api-token: ' . Session::get('x_api_token'),
                'Content-Type: application/json'
            );

            $url    =   config('customparam.udin_base_url') . '/api/v1/payment/postpaid/get-utin-document-rate';
            $x_api_data =   array(
                'method'        =>  'POST',
                'url'           =>  $url,
                'post_field'    =>  $post_field,
                'header'        =>  $header,
            );
            $data   =   curl($x_api_data);
            
            // dd($data);
            if (!$data['error']) {
                DB::table('tbl_stf_fir_dtls')
                    ->where('id',  $id)
                    ->where('stf_fillup_by', $auth_id)
                    ->update(
                        array(
                            'quotation_id'  =>  $data['data']['quotationId']
                        )
                    );
                return $this->generate_document_rate($data, $id);
            } else {
                $reponse = array(
                    'error'     =>  true,
                    'e_code'    =>  $data['code'],
                    'message'   =>  $data['message']
                );
                return response(json_encode($reponse), 200);
            }
        }

    }
    
    

    function generate_document_rate($data, $id)
    {
        $token      =   Session::get('udin_token');
        $auth_id    =   Session::get('auth_id');
        $qt_data    =    DB::table('tbl_stf_fir_dtls')
            ->where('stf_fillup_by', $auth_id)
            ->where('id',  $id)
            ->first();
        
        $post_field_arr  = array(
            'quotationId'  =>   $qt_data->quotation_id,
            'redirectUrl'  => 'redirect'
        );
        //dd($post_field_arr);
        $post_field =  json_encode($post_field_arr);
        $header     =  array(
            'x-auth-token: ' . $token,
            'x-api-token: ' . Session::get('x_api_token'),
            'Content-Type: application/json'
        );

        $url    =   config('customparam.udin_base_url') . '/api/v1/payment/postpaid/generate-udin-document-payment';
        $x_api_data =   array(
            'method'        =>  'POST',
            'url'           =>  $url,
            'post_field'    =>  $post_field,
            'header'        =>  $header
        );
        $data   =   curl($x_api_data);
        /*$s = Session::has('udin_session_aadhar_verifyed');
        echo $s;*/
       
        if (!$data['error']) {
            DB::table('tbl_stf_fir_dtls')
                ->where('stf_fillup_by', $auth_id)
                ->where('id',  $id)
                ->update(
                    array(
                        'transaction_id'  =>  $data['data']['transactionId']
                    )
                );
            return $this->upload_utin_data($data,  $id);
        } else {
            $reponse = array(
                'error'     =>  true,
                'e_code'    =>  $data['code'],
                'message'   =>  $data['message']
            );
            return response(json_encode($reponse), 200);
        }
        
    }

    public function logout(){
        Session::flush();
        $response   =   array(
            'error'     =>  false,
            'message'   =>  'You have logedout Successfully'
        );
        return response()->json($response, 200);
        // return redirect('/admin/login')->with('message', 'success|You have logedout Successfully');
    }

    public function upload_utin_data($data,  $id)
    {
        $token      =   Session::get('udin_token');
        $auth_id    =   Session::get('auth_id');

        $qt_data     =    DB::table('tbl_stf_fir_dtls')
            ->join('tbl_stf_accused_dtls', 'tbl_stf_fir_dtls.id', '=', 'tbl_stf_accused_dtls.acc_fir_id')
            ->where('tbl_stf_fir_dtls.id',  $id)
            ->where('stf_fillup_by', $auth_id)
            ->first();
        
        $template_id    =   null;
        if($qt_data->stf_notice_id    ==  "Notice_35"){
            $template_id    =   config('customparam.stf_notice35_temp_id');
        }elseif($qt_data->stf_notice_id    ==  "Notice_67"){
            $template_id    =   config('customparam.stf_notice67_temp_id');
        }elseif($qt_data->stf_notice_id    ==  "Notice_94"){
            $template_id    =   config('customparam.stf_notice94_temp_id');
        }else{
            $template_id    =   config('customparam.stf_notice179_temp_id');
        }
        $keyword_arr    =  array(
            '_case_fir_no'                  => $qt_data->stf_case_fir_id,
            '_us'                           => $qt_data->stf_us,
            '_fir_date'                     => $qt_data->stf_fir_date,
            '_police_station'               => $qt_data->stf_ps,
            '_diary_no'                     => $qt_data->stf_diary_no,
            '_diary_date'                   => $qt_data->stf_diary_date,
            '_city'                         => $qt_data->stf_city,
            '_name_of_the_accused'          => $qt_data->acc_name,
			'_last_known_address'           => $qt_data->acc_last_address,
			'_social_ph_mailid'		        => $qt_data->acc_ph_mail,
            '_appear_time'                  => $qt_data->acc_appear_time,
            '_appear_date'                  => $qt_data->acc_appear_date,
            '_appear_place'                 => $qt_data->acc_appear_place,
            '_receiver_name'                => $qt_data->stf_receiver_name,
            '_receiving_date'               => $qt_data->stf_receiving_date,
            '_receiving_place'              => $qt_data->stf_receiving_place,
            '_officer_designation'          => $qt_data->stf_officer_designation,
            '_place_of_posting'             => $qt_data->stf_place_of_posting,
            '_officer_ph_no'                => $qt_data->stf_officer_ph_no,
            '_stf_complinant_name'          => $qt_data->stf_complinant_name,
            '_stf_complinant_father_name'   => $qt_data->stf_complinant_father_name,
            '_stf_complinant_address'       => $qt_data->stf_complinant_address,
            '_stf_notice_doc_dtls'          => json_decode($qt_data->stf_notice_doc_dtls),
            '_id'                           => $qt_data->id
        );



        $post_field_arr  = array(
            'quotation_id'      =>  $qt_data->quotation_id,
            'transaction_id'    =>  $qt_data->transaction_id,
            'doc_visibility'    =>  'PUBLIC',
            'doc_type'          =>  'C',
            'key_word'          =>  json_encode($keyword_arr),
            'template_id'       =>  $template_id
        );


        $post_field =  json_encode($post_field_arr);
        $header     =  array(
            'x-auth-token: ' . $token,
            'x-api-token: ' . Session::get('x_api_token'),
            'Content-Type: application/json'
        );

        $url    =   config('customparam.udin_base_url') . '/api/v1/document/upload/utin';
        $x_api_data =   array(
            'method'        =>  'POST',
            'url'           =>  $url,
            'post_field'    =>  $post_field,
            'header'        =>  $header
        );
        $data   =   curl($x_api_data);
      
        if (!$data['error']) {
            DB::table('tbl_stf_fir_dtls')
                ->where('stf_fillup_by', $auth_id)
                ->where('id',  $id)
                ->update(
                    array(
                        'uploaded'  =>  1
                    )
                );

           return $this->getudin($id);
        } else {
           
            $reponse = array(
                'error'     =>  true,
                'e_code'    =>  $data['code'],
                'message'   =>  $data['message']
            );
            return response(json_encode($reponse), 200);
        }
    }


    function getudin($id)
    {
        sleep(10);
        $token      =   Session::get('udin_token');
        $auth_id    =   Session::get('auth_id');

        $qt_data     =    DB::table('tbl_stf_fir_dtls')
            ->where('stf_fillup_by', $auth_id)
            ->where('id',  $id)
            ->first();
        $post_field_arr  = array(
            'quotation_id'  =>  $qt_data->quotation_id
        );

        $post_field =  json_encode($post_field_arr);
        $header     =  array(
            'x-auth-token: ' . $token,
            'x-api-token: ' . Session::get('x_api_token'),
            'Content-Type: application/json'
        );

        $url    =   config('customparam.udin_base_url') . '/api/v1/document/upload/get-udin-number-by-quotationId';
        $x_api_data =   array(
            'method'        =>  'POST',
            'url'           =>  $url,
            'post_field'    =>  $post_field,
            'header'        =>  $header
        );
        $data   =   curl($x_api_data);
       
        if ($data['error'] == false) {
            DB::table('tbl_stf_fir_dtls')
                ->where('stf_fillup_by', $auth_id)
                ->where('id',  $id)
                ->update(
                    array(
                        'udin'  =>   $data['data'][0]['udin'],
                        'is_generate'=>'1'

                    )
                );
                
                $reponse = array(
                    'error'     =>  false,
                    'e_code'    =>  $data['code'],
                    'message'   =>  $data['message']
                );
                return response(json_encode($reponse), 200);
        } else {
            $reponse = array(
                'error'     =>  true,
                'e_code'    =>  $data['code'],
                'message'   =>  $data['message']
            );
            return response(json_encode($reponse), 200);
        }
    }

    public function getUDINno(Request $request){
       /*dd($request["ref"]);*/
        $id      =   $request["ref"];
        $token      =   Session::get('udin_token');
        $auth_id    =   Session::get('auth_id');

        $qt_data     =    DB::table('tbl_stf_fir_dtls')
            ->where('stf_fillup_by', $auth_id)    
            ->where('id',  $id)
            ->first();

        $post_field_arr  = array(
            'quotation_id'  =>  $qt_data->quotation_id
        );

        $post_field =  json_encode($post_field_arr);
        $header     =  array(
            'x-auth-token: ' . $token,
            'x-api-token: ' . Session::get('x_api_token'),
            'Content-Type: application/json'
        );

        $url    =   config('customparam.udin_base_url') . '/api/v1/document/upload/get-udin-number-by-quotationId';
        $x_api_data =   array(
            'method'        =>  'POST',
            'url'           =>  $url,
            'post_field'    =>  $post_field,
            'header'        =>  $header
        );
        $data   =   curl($x_api_data);
       
        if ($data['error'] == false) {
            DB::table('tbl_stf_fir_dtls')
                ->where('stf_fillup_by', $auth_id)    
                ->where('id',  $id)
                ->update(
                    array(
                        'udin'  =>   $data['data'][0]['udin'],
                        'is_generate'=>'1'

                    )
                );
                $reponse = array(
                    'error'     =>  false,
                    'e_code'    =>  $data['code'],
                    'message'   =>  $data['message']
                );
                return response(json_encode($reponse), 200);
        } else {
            $reponse = array(
                'error'     =>  true,
                'e_code'    =>  $data['code'],
                'message'   =>  $data['message']
            );
            return response(json_encode($reponse), 200);
        }
    }
    
    function download_document(Request $request){
        $token      =   Session::get('udin_token');
        $udin_no   =    $request->ref;
        $auth_id    =   Session::get('auth_id');
        /*echo $udin_no;*/

        $data  =   DB::table('tbl_stf_fir_dtls')
                    ->where('stf_fillup_by', $auth_id)  
                    ->where('udin', $udin_no)
                    ->get();


        if($data[0]->udin==null)
        {
            $result = array(
                'error'   =>true,
                'message' =>'Udin is not generated yet!!Try again.'
            );
            return $result;
        }else{

            $token          =   Session::get('udin_token');
        
            $post_field_arr  = array(
                'udin'         =>  $udin_no
            );

            $post_field =  json_encode($post_field_arr);
            $header     =  array(
                'x-api-token: ' . Session::get('x_api_token'),
                'x-auth-token: ' . $token,
                'Content-Type: application/json'
            );

            $url    =   config('customparam.udin_base_url') . '/api/v1/document/verification/verify-udin';
            $x_api_data =   array(
                'method'        =>  'POST',
                'url'           =>  $url,
                'post_field'    =>  $post_field,
                'header'        =>  $header
            );
            $resp   =   curl($x_api_data);
            // dd($resp,$token);
                if($resp["error"]){

                    $ret_data = array(
                        'error' => true,
                        'status'=> $resp["data"]["document"]["status_code"],
                        'status_text'=>$resp["data"]["document"]["status_text"],
                        'docName'=> $resp["data"]["document"]["document_data"]["doc_name"],
                        'message'=>$resp["message"]
                    );

                    return  $ret_data;
                }else{
                        /*$base_64_certificate  = $resp["document"]["document_data"]["doc_base64"];  */ 
                        $base_64_original  = $resp["data"]["document"]["document_data"]["doc_original_base64"];

                        $ret_data = array(
                            'error'=>false,
                            'data_original'=>$base_64_original,
                            'filename'=> $resp["data"]["document"]["document_data"]["doc_meta"]["__udin"]
                        );

                        return  $ret_data;
                }
        }

    }

    public function download_multi_document(Request $request){
      /*dd($request->ref[0]);*/
        $auth_id    =   Session::get('auth_id');
        $post_field_arr  = array(
            'udin'         =>  $request->ref,
            'random'       =>  Session::get('random')
        );
        $post_field =  http_build_query($post_field_arr);

            $header     =  array(
                'X-Api-Token: ' . Session::get('x_api_token'),
                'Content-Type: application/x-www-form-urlencoded'
            );

            $url    =   config('customparam.udin_base_url') . '/api/verify/multiple-udin';
            $x_api_data =   array(
                'method'        =>  'POST',
                'url'           =>  $url,
                'post_field'    =>  $post_field,
                'header'        =>  $header
            );
           
            $resp   =   curl($x_api_data);
         //print_r($resp);die();
            if($resp["error"]){
                $ret_data = array(
                    'error' => true,
                    'message'=> "Unable to download!!"
                );

                return  $ret_data;
            }else{

                for($i=0;$i<count($request->ref);$i++){
                    DB::table('tbl_stf_fir_dtls')
                    ->where('stf_fillup_by', $auth_id)  
                    ->where('udin', $request->ref[$i])
                    ->update(
                        [
                            'is_downloaded'=>1
                        ]
                    );
                }

                $ret_data = array(
                    'error'=>false,
                    'data_original'=>$resp["document"]
                );

                return  $ret_data;

            }
    }
}