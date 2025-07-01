@extends('admin.layouts.login')
@section('page_title', 'LogIn')
@section('content')

<div id="login-page">
    
    <div class="container">
        
        <form class="form-login" action="">
            {{ Form::open(['url' => 'javascript:void(0)', 'method' => 'post',
            'id' => '', 'class' => 'form-login',
            'autocomplete'=>"of",'files'=>'true']) }}
            
            <h2 class="form-login-heading">
                <img src="{{env('APP_URL')}}/assets/admin/img/wb-logo.png" alt=""><br>
                sign in now
            </h2>

            <div class="login-wrap phone-div">
              <input type="text" class="form-control form-control-sm" name="udin_phone" id="udin_phone" placeholder="UDIN PHONE NO" autofocus>
              <br>
              <button  type="button"  class="btn btn-lg px-5 py-2 btn-block btn-send-otp mt-2" style="background: #584090;color:white" href="javascript:void(0)" type="submit"><i class="fa fa-lock"></i> SEND OTP</button>


          </div>

          <div class="login-wrap otp-div hide">

              <input type="password" class="form-control"  id="udin_otp" name="udin_otp" placeholder="OTP">


            <br/>
            <button type="button" class="btn btn-lg px-5 py-2 btn-block mt-2 btn-verify-otp" style="background: #584090;color:white" style="background: #584090;color:white"  href="javascript:void(0)" type="submit"><i class="fa fa-lock"></i> SIGN IN</button>


        </div>

        </form>

    </div>
</div>

@stop
