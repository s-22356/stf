@extends('user.layouts.public') @section('page_title', 'Homepage') @section('content')
<!-- banner section start -->
{{-- <div class="banner_section layout_padding">
    <div class="container">
      <div id="my_slider" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="row">
              <div class="col-md-6">
                <h1 class="video_text">Video games</h1>
                <h1 class="controller_text">controller</h1>
                <p class="banner_text">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable</p>
                <div class="shop_bt"><a href="#">Shop Now</a></div>
              </div>
              <div class="col-md-6">
                <div class="image_1"><img src="{{app_url()}}/assets/user/images/img-1.png"></div>
            </div>
          </div>
          </div>
          <div class="carousel-item">
            <div class="row">
              <div class="col-md-6">
                <h1 class="video_text">Video games</h1>
                <h1 class="controller_text">controller</h1>
                <p class="banner_text">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable</p>
                <div class="shop_bt"><a href="#">Shop Now</a></div>
              </div>
              <div class="col-md-6">
                <div class="image_1"><img src="{{app_url()}}/assets/user/images/img-1.png"></div>
            </div>
          </div>
          </div>
          <div class="carousel-item">
            <div class="row">
              <div class="col-md-6">
                <h1 class="video_text">Video games</h1>
                <h1 class="controller_text">controller</h1>
                <p class="banner_text">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable</p>
                <div class="shop_bt"><a href="#">Shop Now</a></div>
              </div>
              <div class="col-md-6">
                <div class="image_1"><img src="{{app_url()}}/assets/user/images/img-1.png"></div>
            </div>
          </div>
          </div>
        </div>
        <a class="carousel-control-prev" href="#my_slider" role="button" data-slide="prev">
          <i class="fa fa-angle-left"></i>
        </a>
        <a class="carousel-control-next" href="#my_slider" role="button" data-slide="next">
          <i class="fa fa-angle-right"></i>
        </a>
      </div>
    </div>
  </div> --}}
<!-- banner section end -->
<!-- about section start -->
<div class="about_section layout_padding">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                {{--  <div class="image_2"><img src="/assets/user/images/img-2.png"></div> --}}
                <div class="col-sm-4">

                    <p class="normalc"><a class="btn btn-primary" href="ch_feedback.php">Give Feedback</a></p>
                    <hr class="style-two">
                    <p class="normalc"><a class="btn btn-primary" href="{{env('APP_URL')}}/download_cert">Downlod Certificate</a></p>
                    <hr class="style-two">
                    <p class="normalc"><img src="{{app_url()}}/assets/user/images/webel.png" class="rounded" alt="Logo of GoWB"></p>
                    <p class="normalc">Supported by <b>Webel</b></p>
                    <hr class="style-two">

                    <hr class="style-two">

                    <hr class="d-sm-none">
                </div>
            </div>
            <div class="col-md-6">
                {{--   <h1 class="about_text">ABOUT</h1>
                <p class="lorem_text">There are many variations of passages of Lorem Ipsum available, but the majority
                    have suffered alteration in some form, by injected humour, or randomised words which don't look even
                    slightly believable</p>
                <div class="shop_bt_2"><a href="#">Shop Now</a></div> --}}
                <div class="col-sm-8">
                    <p class="normalc"><b>Cyberer Sahajpath - a Cyber Security Awareness Training programme for the
                            students.</b></p>

                    <hr class="style-two">
                    <hr class="style-two">
                    <p class="normalc">Please spend a minute to fill in this survey form and help us serve you better in
                        future.</p>
                    <p class="normalc"><a href="{{app_url()}}">
                            <font color="blue"><b>Click Here to Give Feedback</b></font>
                        </a></p>
                    <hr class="style-two">
                    <hr class="style-two">

                    {{-- <hr class="style-two">
                    <hr class="style-two"> --}}
                    <p class="normalc"><a href="{{app_url()}}">
                            <font color="blue"><b>Click Here to Download Certificate</b></font>
                        </a></p>
                    <hr class="style-two">
                    <hr class="style-two">

                </div>
            </div>
        </div>
    </div>
</div>


@stop
