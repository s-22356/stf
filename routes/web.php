<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\PageController;

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
Route::get('/', [PageController::class, 'showHome']);
Route::get('/download_cert', [PageController::class, 'download_cert']);

Route::post('/certificate-download', [PageController::class, 'cetificatedownload']);

Route::post('/validate-otp', [PageController::class, 'validate_otp']);
Route::post('/download-utin-doc', [PageController::class, 'downloadUTINcert']);
