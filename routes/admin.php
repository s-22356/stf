<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\AdminPostController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get( '/', [AdminController::class, 'login']);
Route::get( '/logAuthDev', [AdminController::class, 'logAuthDev']);
Route::get( '/login', [AdminController::class, 'login']);
Route::post('/generate-login-phone-otp',[AdminPostController::class, 'generate_login_phone_otp']);
Route::post('/verify-login-phone-otp',[AdminPostController::class, 'verify_login_phone_otp']);

/*---------------------Verify Authorised Person-----------------------------*/

Route::get('/send-otp-to-phone-future-auth',[AdminController::class, 'send_phone_OtpFutureAuthPrsn']);
Route::post('/verify-auth-request-data',[AdminController::class, 'VerifyAuthReqData']);
Route::post('/generate-auth-adhr-otp',[AdminController::class, 'GenerateAuthAdhrOtp']);
Route::post('/verify-auth-aadhaar-otp',[AdminController::class, 'VerifyAuthAdhrOtp']);

/*---------------------------END-----------------------------------------*/

Route::group(['middleware' => ['verifyLoginToken']], function () {
    Route::get( '/dashboard', [AdminController::class, 'dashboard']);
    Route::post( '/dashboard_individual', [AdminController::class, 'dashboard_individual']);

    Route::get( '/upload/certificate-excel', [AdminController::class, 'upload_certificate_excel']);
    Route::post( '/upload/certificate-excel', [AdminPostController::class, 'upload_certificate_excel']);



    Route::get( '/udin/generate-certificate', [AdminController::class, 'generate_udin_certificate']);
    Route::post( '/generate-aadhar-otp', [AdminPostController::class, 'generate_aadhar_otp']);
    Route::post( '/verify-aadhar-otp', [AdminPostController::class, 'verify_aadhar_otp']);
    Route::post( '/udin/generate-certificate', [AdminPostController::class, 'generate_udin_certificate']);

    Route::get( '/udin/send-auth-request', [AdminController::class, 'SendAuthRequest']);
    Route::post( '/udin/send-auth-request-data', [AdminController::class, 'SendAuthRequestData']);
    Route::post( '/udin/delete-auth-request-data', [AdminController::class, 'DeleteAuthRequestData']);
    Route::post( '/udin/auth-actv-dactv-status', [AdminController::class, 'AuthActiveDeactiveStatus']);
    
    Route::get( '/udin/generated-certificate', [AdminController::class, 'generated_udin_certificate']);

    Route::post( '/udin/download-udin-doc', [AdminPostController::class, 'download_document']);
    Route::post( '/udin/download-multiple-doc', [AdminPostController::class, 'download_multi_document']);
    Route::get( '/udin/downloaded-certificate', [AdminController::class, 'downloaded_certificate']);
    Route::post('/udin/get-udin-no', [AdminPostController::class, 'getUDINno']);
    
    Route::get('/logout', [AdminPostController::class, 'logout']);

});

