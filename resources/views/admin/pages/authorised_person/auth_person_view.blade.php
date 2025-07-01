@extends('admin.layouts.public')
@section('page_title', $page_tile)
@section('content')


<section id="main-content">
    <br>
    <section class="wrapper">

        <div class="row">
            <div class="col-lg-12">
            
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb bg-light p-3 rounded">
                        <li class="breadcrumb-item"><a href="#">Authorised Person</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $page_tile }}</li>
                    </ol>
                </nav>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card shadow-lg" style="border-radius: 7px;height:100%;padding:5%;width:100%;">
                            <div class="card-header text-white text-center">
                                Send Request for Authorisation
                            </div><br>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="auth_ph_mobile" class="form-label"><b class="form_fields">Enter Mobile:</b></label>
                                        <input type="mobile" name="auth_ph_mobile" class="form-control" id="auth_ph_mobile" placeholder="e.g., +919876543210" required>
                                    </div>
                                </div><br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-lg px-5 py-2 send-auth-req" style="background: #584090;color:white">Send request</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-lg" style="border-radius: 7px;height:100%;padding:5%;width:100%;">
                            <div class="card-header text-white text-center">
                                Request Received for Authorisation
                            </div><br>
                            <div class="card-body">
                                <table id="datatable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sl</th>
                                            <th>Request Person Phone</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ( $data as $k=>$d)
                                        <tr>
                                            <td>{{ $k+1 }}</td>
                                            <td>{{decryptInfo($d->stf_apr_ph_no)}}</td>
                                            <td>
                                                @if($d->stf_apr_status  !=  'Approved')
                                                    <h4><span class="label label-danger">{{strtoupper($d->stf_apr_status)}}</span></h4>
                                                @else
                                                    <h4><span class="label label-success">{{strtoupper($d->stf_apr_status)}}</span></h4>
                                                @endif
                                            </td>
                                            <td>{{$d->created_at}}</td>
                                            <td>
                                               
                                                @if($d->is_active  !=  '1'  && $d->stf_apr_status  ==  'Approved' && $d->stf_apr_aadhar_verified  ==  '1')
                                                    <div class="col-md-12">
                                                        <button class="btn btn-lg px-5 py-2 auth-actv-dactv-status" data-id='Active' data-phone='{{$d->stf_apr_ph_no}}' style="background:rgb(210, 226, 31);color:white">Active</button>
                                                        <button class="btn btn-lg px-5 py-2 btn-delete-auth" data-ph="{{decryptInfo($d->stf_apr_ph_no)}}" style="background:rgb(207, 76, 76);color:white;margin-top:5%;">Delete</button>
                                                    </div><br><br><br>
                                                @elseif($d->is_active  !=  '0'  && $d->stf_apr_status  ==  'Approved' && $d->stf_apr_aadhar_verified  ==  '1')
                                                    <div class="col-md-12">
                                                        <button class="btn btn-lg px-5 py-2 auth-actv-dactv-status" data-id='Deactive' data-phone='{{$d->stf_apr_ph_no}}' style="background:rgb(224, 18, 18);color:white">Deactive</button>
                                                        <button class="btn btn-lg px-5 py-2 btn-delete-auth" data-ph="{{decryptInfo($d->stf_apr_ph_no)}}" style="background:rgb(207, 76, 76);color:white;margin-top:5%;">Delete</button>
                                                    </div><br><br><br>
                                                @else
                                                    <div class="col-md-12">
                                                        <button class="btn btn-lg px-5 py-2 btn-resend-auth" data-ph="{{decryptInfo($d->stf_apr_ph_no)}}" style="background: #584090;color:white;">Resend</button>
                                                        <button class="btn btn-lg px-5 py-2 btn-delete-auth" data-ph="{{decryptInfo($d->stf_apr_ph_no)}}" style="background:rgb(207, 76, 76);color:white;margin-top:5%;">Delete</button>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</section><br>
                                        
@stop