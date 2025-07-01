<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Session;
use DB;
use Exception;
use App;

class AdminController extends Controller
{

    public function __construct()
    {
        if(Session::has("refresh_token_expired_code") && Session::get("refresh_token_expired_code") == "STFREXP001"){
           
        }

    }
    
    

    public function logAuthDev()
    {
        ///dd(encryptHEXFormat("8240374442", "N5K7J5TWTPQZGS6RKJZJYFR9J3J24Z6W"));
        $data   =   DB::table('log_entries')->get();
        return view('admin.pages.stf_log_dtls.logAuthDev', [
            'module' => 'upload',
            'page_title' => 'Log Entries',
            'data'  =>  $data
        ]);
    }


    public function login(Request $request)
    {
        //dd(encryptInfo("9002675209"));
        return view('admin.pages.login', [
            'module' => 'login',
            'active-page' => 'home'
        ]);
    }


    public function dashboard()
    {
        $auth_id =  Session::get('auth_id');
        
        if ($auth_id!=  '') {
            $data = DB::table('tbl_stf_fir_dtls')
            ->selectRaw('
                SUM(CASE WHEN stf_notice_id = "Notice_35" THEN 1 ELSE 0 END) AS total_notice35_generate,
                SUM(CASE WHEN stf_notice_id = "Notice_67" THEN 1 ELSE 0 END) AS total_notice67_generate,
                SUM(CASE WHEN stf_notice_id = "Notice_179" THEN 1 ELSE 0 END) AS total_notice179_generate,
                SUM(CASE WHEN stf_notice_id = "Notice_94" THEN 1 ELSE 0 END) AS total_notice94_generate,
                COUNT(CASE WHEN stf_notice_id = "Notice_35" AND udin IS NOT NULL THEN 1 END) AS udin_for_notice35,
                COUNT(CASE WHEN stf_notice_id = "Notice_67" AND udin IS NOT NULL THEN 1 END) AS udin_for_notice67,
                COUNT(CASE WHEN stf_notice_id = "Notice_94" AND udin IS NOT NULL THEN 1 END) AS udin_for_notice94,
                COUNT(CASE WHEN stf_notice_id = "Notice_179" AND udin IS NOT NULL THEN 1 END) AS udin_for_notice179,
                COUNT(CASE WHEN udin IS NOT NULL THEN 1 END) AS total_udin
            ')
            ->where('stf_fillup_by', $auth_id)
            ->first();
        }
           
        $authId = DB::table('tbl_stf_auth_prsn_req')
            ->join('tbl_stf_auth_prsn', 'tbl_stf_auth_prsn_req.stf_apr_ph_no', '=', 'tbl_stf_auth_prsn.stf_auth_phone')
            ->where('tbl_stf_auth_prsn_req.stf_apr_status', 'Approved')
            ->where('tbl_stf_auth_prsn_req.is_active', 1)
            ->where('tbl_stf_auth_prsn.stf_auth_type', 'auth_user')
            ->get();

        
            return view('admin.pages.dashboard', [
                'module' => 'dashboard',
                'active_page' => 'dashboard',
                'page_tile' => 'Special Task Force Notice DashBoard',
                'data' => $data,
                'authId' => Session::get('auth_type') != "auth_user"   ?    $authId :   ''
            ]);
        
    }

    public function dashboard_individual( Request $request ){

		if($request->authID	!=	'ALL'){
		
			$data = DB::table('tbl_stf_fir_dtls')
			->selectRaw('
				SUM(CASE WHEN stf_notice_id = "Notice_35" THEN 1 ELSE 0 END) AS total_notice35_generate,
				SUM(CASE WHEN stf_notice_id = "Notice_67" THEN 1 ELSE 0 END) AS total_notice67_generate,
				SUM(CASE WHEN stf_notice_id = "Notice_179" THEN 1 ELSE 0 END) AS total_notice179_generate,
				SUM(CASE WHEN stf_notice_id = "Notice_94" THEN 1 ELSE 0 END) AS total_notice94_generate,
				COUNT(CASE WHEN stf_notice_id = "Notice_35" AND udin IS NOT NULL THEN 1 END) AS udin_for_notice35,
				COUNT(CASE WHEN stf_notice_id = "Notice_67" AND udin IS NOT NULL THEN 1 END) AS udin_for_notice67,
				COUNT(CASE WHEN stf_notice_id = "Notice_94" AND udin IS NOT NULL THEN 1 END) AS udin_for_notice94,
				COUNT(CASE WHEN stf_notice_id = "Notice_179" AND udin IS NOT NULL THEN 1 END) AS udin_for_notice179,
				COUNT(CASE WHEN udin IS NOT NULL THEN 1 END) AS total_udin
			')
			->where('stf_fillup_by', $request->authID)
			->first();

			$authName = DB::table('tbl_stf_auth_prsn')
						->where('stf_auth_id', $request->authID)
						->value('stf_auth_name');
		}else{
			
			$data = DB::table('tbl_stf_fir_dtls')
			->selectRaw('
				SUM(CASE WHEN stf_notice_id = "Notice_35" THEN 1 ELSE 0 END) AS total_notice35_generate,
				SUM(CASE WHEN stf_notice_id = "Notice_67" THEN 1 ELSE 0 END) AS total_notice67_generate,
				SUM(CASE WHEN stf_notice_id = "Notice_179" THEN 1 ELSE 0 END) AS total_notice179_generate,
				SUM(CASE WHEN stf_notice_id = "Notice_94" THEN 1 ELSE 0 END) AS total_notice94_generate,
				COUNT(CASE WHEN stf_notice_id = "Notice_35" AND udin IS NOT NULL THEN 1 END) AS udin_for_notice35,
				COUNT(CASE WHEN stf_notice_id = "Notice_67" AND udin IS NOT NULL THEN 1 END) AS udin_for_notice67,
				COUNT(CASE WHEN stf_notice_id = "Notice_94" AND udin IS NOT NULL THEN 1 END) AS udin_for_notice94,
				COUNT(CASE WHEN stf_notice_id = "Notice_179" AND udin IS NOT NULL THEN 1 END) AS udin_for_notice179,
				COUNT(CASE WHEN udin IS NOT NULL THEN 1 END) AS total_udin
			')
			->first();

			$authName = 'ALL';
		}
        

        $reponse = array(
            'error'     =>  false,
            'data'      =>  $data,
            'auth_name' =>  $authName
        );
        return response(json_encode($reponse), 200);
    }
    
    public function upload_certificate_excel(Request $request  )
    {

        return view('admin.pages.upload_certificate_excel', [
            'module' => 'upload',
            'active_page' => 'certificate-excel',
            'page_tile'   => 'Special Task Force Form',
        ]);
    }


    public function generate_udin_certificate(Request $request)
    {
      //  dd(Session::all());
        $auth_id    =   Session::get('auth_id');
        $data   =   DB::table('tbl_stf_fir_dtls')
        ->where('uploaded',0)
        ->where('is_generate',0)
        ->where('stf_fillup_by', $auth_id)
        ->get();
        //dd($data);
        return view('admin.pages.generate_udin_certificate', [
            'module' => 'udin',
            'active_page' => 'udin-generate',
            'page_tile'   => 'Generate UDIN',
            'data'        =>    $data
        ]);
    }


    public function generated_udin_certificate(Request $request)
    {
        $auth_id    =   Session::get('auth_id');
        $data   =   DB::table('tbl_stf_fir_dtls')
        ->where('uploaded',1)
        //->where('is_generate',1)
        ->where('is_downloaded',0)
        ->where('stf_fillup_by', $auth_id)
        ->get();
        //dd($data);
        return view('admin.pages.generated_udin_certificate', [
            'module' => 'udin',
            'active_page' => 'udin-generated',
            'page_tile'   => 'Generated UDIN ',
            'data'          =>  $data
        ]);
    }

    public function downloaded_certificate(){

        $auth_id    =   Session::get('auth_id');
        $data   =   DB::table('tbl_stf_fir_dtls')
        ->where('uploaded',1)
        ->where('stf_fillup_by', $auth_id)
        ->get();

        return view('admin.pages.downloaded_udin_certificate', [
            'module' => 'udin',
            'active_page' => 'download-document',
            'page_tile'   => 'downloaded UDIN ',
            'data'          =>  $data
        ]);
    }

    public function SendAuthRequest(){
        
        $auth_id        =   Session::get('auth_id');
        $authPrsnReqStatus  =   DB::table('tbl_stf_auth_prsn_req')
                                ->where('stf_aprq_req_by', $auth_id)
                                ->where('is_delete','0')
                                ->get();
        //dd($authPrsnReqStatus);
        return view('admin.pages.authorised_person.auth_person_view', [
            'module' => 'auth_person',
            'active_page' => 'add-auth-reqest',
            'page_tile'   => 'Send request to be authorised person',
            'data'        => $authPrsnReqStatus
        ]);
    }

    public function DeleteAuthRequestData(Request $request){

        $auth_req_ph    =   $request->ref;

        if($auth_req_ph ==  ""  ||  strlen($auth_req_ph) < 10){

            $ret_data = array(
                "error"     => true,
                "message"   => "Please enter a valid mobile number."
            );        

            return  $ret_data;
        }

        if(DB::table('tbl_stf_auth_prsn_req')
            ->where('is_delete','0')
            ->where('stf_apr_ph_no', encryptInfo($auth_req_ph))
            ->exists()){

                DB::table('tbl_stf_auth_prsn_req')
                ->where('stf_apr_ph_no', encryptInfo($auth_req_ph))
                ->update([
                    'is_delete' =>  '1',
                    'is_active' =>  '0'
                ]);
               
                $ret_data = array(
                    "error"     => false,
                    "message"   => "Request deleted successfully."
                );        
    
                return  $ret_data;

        }else{
            $ret_data = array(
                "error"     => true,
                "message"   => "Authorization request profile not found."
            );        

            return  $ret_data;
        }
    }

    public function SendAuthRequestData(Request $request){
        
        $auth_req_ph    =   $request->ref;
        $auth_id        =   Session::get('auth_id');
        $token          =   Session::get('udin_token');
        $root_url       =   config('customparam.APP_URL');
        
        if($auth_req_ph ==  ""  ||  strlen($auth_req_ph) < 10){

            $ret_data = array(
                "error"     => true,
                "message"   => "Please enter a valid mobile number."
            );        

            return  $ret_data;
        }

        if(DB::table('tbl_stf_auth_prsn_req')
            ->where('stf_apr_aadhar_verified', '1')
            ->where('stf_apr_status','Approved')
            ->where('is_active','1')
            ->where('is_delete','0')
            ->where('stf_apr_ph_no', encryptinfo($auth_req_ph))
            ->exists()){

            $ret_data = array(
                "error"     => true,
                "message"   => "Already Approved."
            );        

            return  $ret_data;

        }
        
        if (DB::table('tbl_stf_auth_prsn_req')
            ->where(function($query) {
                $query->where('stf_apr_status', 'Approved')
                      ->orWhere('stf_apr_status', 'Pending');
            })
            ->where('is_active', '0')
            ->where('is_delete', '1')
            ->where('stf_apr_ph_no', encryptinfo($auth_req_ph))
            ->exists()) {
            
            $ret_data = array(
                "error"     => true,
                "message"   => "Authorization request profile was deleted."
            );        
    
            return  $ret_data;
        }
       

        $post_field_arr  = array(
            'phone'         =>  $auth_req_ph,
            'accessToAdd'   =>  true,
            'redirectUrl'      =>  $root_url.'/admin/send-otp-to-phone-future-auth'
        );

        $post_field =  json_encode($post_field_arr);
        $header     =  array(
            'x-api-token: ' . Session::get('x_api_token'),
            'x-auth-token: ' . $token,
            'Content-Type: application/json'
        );

        $url    =   config('customparam.udin_base_url') . '/api/v1/auth/service-auth-person/create-auth-person-phone-request';
        $x_api_data =   array(
            'method'        =>  'POST',
            'url'           =>  $url,
            'post_field'    =>  $post_field,
            'header'        =>  $header
        );
        $data   =   curl($x_api_data);

        if($data['error'] == false){

            $sendAuthReq    =   DB::table('tbl_stf_auth_prsn_req')
            ->where('stf_apr_ph_no', encryptInfo($auth_req_ph))
            ->where('stf_apr_aadhar_verified', '0')
            ->where('stf_apr_status','Pending')
            ->where('is_active','0')
            ->exists();
            if(!$sendAuthReq){
                $authPrsnReqData    =   array(
                    'stf_apr_ph_no'     =>  encryptInfo($auth_req_ph),
                    'stf_apr_status'    =>  'Pending',
                    'stf_aprq_req_by'   =>  $auth_id
                );
                DB::table('tbl_stf_auth_prsn_req')->insert($authPrsnReqData);
            }

            $ret_data = array(
                "error"     => false,
                "code"      => $data["status"],
                "message"   => $data["message"]
            );
            return  $ret_data;
        }else{
            $ret_data = array(
                "error"     => true,
                "code"      => $data["status"],
                "message"   => $data["message"]
            );
            return  $ret_data;
        }
    }

    public function AuthActiveDeactiveStatus(Request $request){
        /*dd($request->all());*/
        $status         =   $request->status;
        $auth_req_ph    =   $request->req_ph;

        if($status  ==  ""  ||  $auth_req_ph  ==  ""){
            $validation =   array(
                "error"     =>  true,
                "message"   =>  "Please try again."
            );
            return $validation;
        }
        if($status  ==  "Deactive"){
            DB::table("tbl_stf_auth_prsn_req")
            ->where("stf_apr_ph_no",  $auth_req_ph)
            ->update([
                "is_active" =>  '0'
            ]);

            $response   =   array(
                "error"     =>  false,
                "message"   =>  "This Phone Number ".decryptInfo($auth_req_ph)."is Blocked."    
            );
            return $response;
        }else{
            DB::table("tbl_stf_auth_prsn_req")
            ->where("stf_apr_ph_no",  $auth_req_ph)
            ->update([
                "is_active" =>  '1'
            ]);

            $response   =   array(
                "error"     =>  false,
                "message"   =>  "This Phone Number ".decryptInfo($auth_req_ph)."is Unblocked."    
            );
            return $response;
        }
        
    }

    public function send_phone_OtpFutureAuthPrsn(){

        return view('admin.pages.authorised_person.verify_auth_person_view', [
            'module' => 'verify_auth_person',
            'active_page' => 'verify-auth-person',
            'page_tile'   => 'Verify request to be authorised person'
        ]);
    }
/*************************** This two api ********************************** */
    public function VerifyAuthReqData(Request $request){
        
        $ph_no          =   $request->req_phone;
        $secret_id      =   $request->secret_id;
        // $aadhaar_no     =   $request->aadhar_no;
        
        $post_field_arr  = array(
            'phone'         =>  $ph_no,
            'secretKey'     =>  $secret_id
        );

        $post_field =  json_encode($post_field_arr);
        $header     =  array(
            'x-api-token: ' . Session::get('x_api_token'),
            'Content-Type: application/json'
        );
        // dd($post_field , $header);
        $url    =   config('customparam.udin_base_url') . '/api/v1/auth/admin/auth-person/verify-auth-person-phone-request';
        $x_api_data =   array(
            'method'        =>  'POST',
            'url'           =>  $url,
            'post_field'    =>  $post_field,
            'header'        =>  $header
        );
        $data   =   curl($x_api_data);
        
        if(!$data['error']){

            $ret_data = array(
                "error"         => false,
                "code"          => $data["code"],
                "message"       => $data["message"],
                "trans_id"      => $data["data"]["transactionId"],
                "orgName"       => $data["data"]["orgName"],
                "refId"         => $data["data"]["refId"],
                "adhr_asct_ph"  => encryptInfo($ph_no)
            );
            return $ret_data;
        }else{

            $ret_data = array(
                "error"     => true,
                "code"      => $data["code"],
                "message"   => $data["message"]
            );
            return $ret_data;
        }

    }

    public function GenerateAuthAdhrOtp(Request $request){

        $authPersonRefId       =   $request->adhr_refId;
        $aadhaarNumber            =   $request->aadhar_no;

        $post_field_arr  = array(
            'aadhaarNumber'     =>  $aadhaarNumber,
            'authPersonRefId'   =>  $authPersonRefId
        );
        $post_field =  json_encode($post_field_arr);
        $header     =  array(
            'x-api-token: ' . Session::get('x_api_token'),
            'Content-Type: application/json'
        );
        $url    =   config('customparam.udin_base_url') . '/api/v1/auth/admin/auth-person/verify-auth-person-aadhaar';
        $x_api_data =   array(
            'method'        =>  'POST',
            'url'           =>  $url,
            'post_field'    =>  $post_field,
            'header'        =>  $header
        );
        $data   =   curl($x_api_data);
        // dd($data);
        if($data['error'] == false){

            $ret_data = array(
                "error"                 => false,
                "code"                  => $data["code"],
                "message"               => $data["message"],
                "adhr_transaction_id"   => $data["data"]["transactionId"],
                "authPersonRefId"       => $authPersonRefId
            );
            return $ret_data;
        }else{
            $ret_data = array(
                "error"     => true,
                "code"      => $data["code"],
                "message"   => $data["message"]
            );
            return $ret_data;
        }
    }




    public function VerifyAuthAdhrOtp(Request $request){

        $adhr_trans_id      =   $request->adhr_trans_id;
        $aadhar_otp         =   $request->aadhar_otp;
        $adhr_refId         =   $request->adhr_refId;
        $aadhar_asct_ph     =   $request->aadhar_asct_ph;

        $post_field_arr  = array(
            'otp'              =>  $aadhar_otp,
            'transactionId'    =>  $trans_id,
            'authPersonRefId'  =>  $adhr_refId
        );

        $post_field =  json_encode($post_field_arr);
        $header     =  array(
            'x-api-token: ' . Session::get('x_api_token'),
            'Content-Type: application/json'
        );

        $url    =   config('customparam.udin_base_url') . '/api/v1/auth/admin/auth-person/create-auth-person';
        $x_api_data =   array(
            'method'        =>  'POST',
            'url'           =>  $url,
            'post_field'    =>  $post_field,
            'header'        =>  $header
        );
        $data   =   curl($x_api_data);

        if($data['error']   ==  false){

            $AuthPrsnReqData    =   array(
                'stf_apr_aadhar_verified'   =>  '1',
                'stf_apr_status'            =>  'Approved',
                'is_active'                 =>  '1'
            );
            DB::table('tbl_stf_auth_prsn_req')->where('stf_apr_ph_no',$aadhar_asct_ph)->update($AuthPrsnReqData);
            $ret_data = array(
                "error"         => false,
                "code"          => $data["code"],
                "message"       => $data["message"]
            );
            return $ret_data;

        }else{
            $ret_data = array(
                "error"         => true,
                "code"          => $data["code"],
                "message"       => $data["message"]
            );
            return $ret_data;
            
        }
    }

    /***************************END This two api ********************************** */
}
