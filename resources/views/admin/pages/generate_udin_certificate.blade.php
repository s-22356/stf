@extends('admin.layouts.public')
@section('page_title', $page_tile)
@section('content')
    <section id="main-content">
        <section class="wrapper">

            
                <div class="col-lg-6 main-chart">
                
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">UDIN</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $page_tile }}</li>
                        </ol>
                    </nav>
                    <div class="card shadow-lg" style="border-radius: 7px;height:100%;padding:3%;">
                        <div class="card-body p-4">
                            @if (Session::has('udin_session_aadhar_verifyed'))
                                <!-- BASIC FORM ELELEMNTS -->
                                <div class="row mt">
                                    <div class="col-lg-12">
                                      

                                        @if(sizeof($data))
                                            <div class="card shadow-lg" style="border-radius: 7px;height:100%;padding:3%;">
                                                <div class="card-body p-4">
                                                    <table id="datatable" class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl</th>
                                                                <th>Case Fir Id</th>
                                                                <th class="numeric">Diary Number</th>
                                                                <th class="numeric">Created At</th>
                                                                <th class="numeric">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ( $data as $k=>$d)
                                                                <tr>
                                                                    <td>{{ $k+1 }}</td>
                                                                    <td>{{ $d->stf_case_fir_id }}</td>
                                                                    <td class="numeric">{{ $d->stf_diary_no }}</td>
                                                                    <td class="numeric">{{ $d->created_at }}</td>
                                                                    <td class="numeric">
                                                                        <button data-id="{{ $d->id }}" type="button" class="btn btn-lg px-5 py-2 btn-generate-udin" style="background: #584090;color:white">Generate</button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    
                                                </div>
                                            </div>
                                            @else
                                            <div class="card shadow-lg" style="border-radius: 7px;height:100%;padding:3%;">
                                                <div class="card-body p-4">
                                                    <table id="datatable" class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl</th>
                                                                <th>Case Fir Id</th>
                                                                <th class="numeric">Diary Number</th>
                                                                <th class="numeric">Created At</th>
                                                                <th class="numeric">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            @endif
                                            <input type="hidden" id="id" name="id" value="">
                                            <!-- </form> -->
                                        
                                    </div><!-- col-lg-12-->
                                </div><!-- /row -->
                            @else
                                <form class="form-horizontal" method="get">

                                    <div class="form-group addhar_div">
                                        <label class="col-md-3 control-label"><b>AADHAAR NO :</b></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="udin_aadhaar" id="udin_aadhaar"
                                                value=""><br>
                        
                                        </div>
                                        <div class="form-group col-md-12" style="margin-left: 1px;text-align: justify;">
                                            
                                            <input type="checkbox" name="aadhaar_concent" id="aadhaar_concent">
                                            <b>I hereby state that I have no objection in authenticating myself on Unique Document Identification Number (UDIN) portal * with Aadhaar based authentication system and *give my consent to providing my Aadhaar number, Biometric and/or One-Time Password (OTP) data for Aadhaar based authentication for the Unique Document Identification Number (UDIN) Portal access. I understand that the Aadhaar number, Biometrics and/ or OTP I provide for authentication shall be used for authenticating my identity and the Department of Information Technology & Electronics Government of West Bengal shall ensure security and confidentiality of my personal identity data provided for the purpose of Aadhaar based authentication.</b>
                                           
                                        </div>
                                        <div class="form-group col-md-12 control-label" style="margin-left: 0px;">
                                            <button type="button" class="btn btn-primary btn-lg btn-block mt-2 btn-aadhar-generate-otp "
                                                href="javascript:void(0)" type="submit" disabled> GENERATE OTP</button>
                                        </div>

                                    </div>


                                    <div class="form-group addhar_otp_validate_div hide">
                                        <label class="col-md-3 col-sm-2 control-label"><b>OTP :</b></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name='addhar_otp' id="addhar_otp"
                                                value="">
                                           
                                            <input type="hidden" class="form-control" id='transaction_id' name='transaction_id'
                                                value="">
                                            <input type="hidden" class="form-control" id='addhar_no' name='addhar_no'
                                                value="">
                                        </div>
                                        <div class="form-group col-md-12 control-label" style="margin-left: 0px;">
                                            <button type="button" class="btn btn-primary btn-lg btn-block mt-2 btn-aadhar-verify-otp "
                                                href="javascript:void(0)" type="submit"> VALIDATE OTP</button>
                                        </div>
                                    </div>


                                </form>
                            @endif
                        </div>
                    </div>



                </div><!-- /col-lg-9 END SECTION MIDDLE -->


                <!-- **********************************************************************************************************************************************************
                RIGHT SIDEBAR CONTENT
                *********************************************************************************************************************************************************** -->

                                    
            
            <!--/row -->
        </section>
    </section><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

                                        
@stop
