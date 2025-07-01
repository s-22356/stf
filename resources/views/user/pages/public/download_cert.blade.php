@extends('user.layouts.public')
@section('page_title', 'Download Certificate')
@section('content')

<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Download Certificate</h3>
            </div>
        </div>
    </div>
</div>

<div class="page-wrapper">
    <div class="container">
        <div class="row">
            
                <div class="card">
                    <div class="card-body">

                        <div class="form-group mb-3 generate-otp-wrap">
                                <div class="row">
                                    <div class="col-md-8">
                                        <label class="fw-bold">Mobile Number <span class="required">*</span></label>
                                        <div class="input-group" id="username_container">
                                            <span class="input-group-text" id="phone">+91</span>
                                            <input type="text" class="form-control number" id="username" name="username" aria-describedby="phone" maxlength="10" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div>&nbsp;</div>

                                        <div class="generate-otp-area">
                                            <button type="button" class="btn btn-primary generate-otp">Get OTP</button>
                                        </div>
                                    </div>
                                </div>
                        </div>

                        <div class="form-group mb-3 validate-otp-area"  style="display:none;">
                                <div class="row">
                                    <div class="col-md-8">
                                        <label class="fw-bold">One Time Password <span class="required">*</span>&nbsp;<span class="app_debug_otp"></span></label>
                                        <input type="text" class="form-control number" id="otp" name="otp" maxlength="6">
                                        <small>A six digit code sent to your mobile number</small>
                                        <div id="otp_error"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <div>&nbsp;</div>
                                        <div>
                                            <button type="button" class="btn btn-primary validate-otp">Validate OTP</button>
                                        </div>

                                    </div>
                                </div>
                        </div>
                        <table class="table table-bordered table-striped table-certificate" style="display:none;">
                                    <thead>
                                        <tr>
                                            <th colspan="5">College Name</th>
                                            <th colspan="10">Batch No.</th>
                                            <th colspan="10">UDIN No</th>
                                            <th colspan="10">Registration No.</th>
                                            <th colspan="10">Download</th>
                                        </tr>
                                    </thead>
                                        <tbody id="certificateid">
                                        </tbody>
                                </table>
                    </div>
                </div>
            
            
        </div>

    </div>
</div>

@stop