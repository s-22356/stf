<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title> @yield('page_title') - {{app_short_name()}} </title>

    <!-- Bootstrap core CSS -->
    <link href="{{app_url()}}/assets/admin/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="{{app_url()}}/assets/admin/font-awesome/css/font-awesome.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="{{app_url()}}/assets/admin/css/style.css" rel="stylesheet">
    <link href="{{app_url()}}/assets/admin/css/style-responsive.css" rel="stylesheet">
    <style>

        .modal {
            animation: arrive 0.8s;
        }

        @keyframes arrive {
            0% {
                transform: translateY(-100%) translateX(100%);
            }
            100% {
                transform: translateY(0) translateX(0);
            }
        }
        
        .modal-header .close {
            color:rgb(0, 0, 0);
            background-color:rgb(255, 255, 255);
            width:5%;
            border-radius:5px;
            opacity: 1;
            font-size: 30px;
            font-weight: bold;
        }

        .modal-header {
            border-radius:5px;
            border-bottom: 10px solid rgb(119, 0, 53);
            padding: 15px;
            background-color:rgb(90, 3, 156);
            color: #ffffff;
            font-size: 18px;
            font-weight: bold;
        }

        
        #page_loader {
            display: none;
            background-color: rgba(255, 255, 255, 0.7);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 9999 /* +100 */ !important;
            width: 100%;
            height: 100%;
        }
        #page_loader img {
            position: relative;
            top: 40%;
            left: 45%;
        }
    </style>

  </head>

  <body>

      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->


        <section class="wrapper" style="margin-top:1%;">

            <div class="row justify-content-center">
                <div class="col-lg-12">
                
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb" class="mb-4 form_fields">
                        <ol class="breadcrumb bg-light p-3 rounded">
                            <li class="breadcrumb-item"><a href="#">Verify Auth Request</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $page_tile }}</li>
                        </ol>
                    </nav>

                    <div class="card shadow-lg" style="border-radius: 7px;padding:1%;width:40%;height:50%;">
                        <div class="card-header text-white text-center">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="ph_no" class="form-label"><b class="form_fields">Mobile number on which the request was received *:</b></label>
                                    <input type="mobile" name="ph_no" class="form-control" id="ph_no" placeholder="e.g. 8240776774">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <br><label for="secret_id" class="form-label"><b class="form_fields">Secrect Key *:</b></label>
                                    <input type="text" name="secret_id" class="form-control" id="secret_id" placeholder="e.g. HYKKTH">
                                </div>
                            </div><br>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="btn" class="btn btn-lg form-control verify_req_auth_ph" style="background: #584090;color:white;width:30%;height:20%;margin-right:70%;">Verify</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- Aadhaar Verification Modal -->
    @include('admin.common.aadhaar_verification')

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="{{app_url()}}/assets/admin/js/jquery.js"></script>
    <script src="{{app_url()}}/assets/admin/js/bootstrap.min.js"></script>

    <!--BACKSTRETCH-->
    <!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
    <script type="text/javascript" src="{{app_url()}}/assets/admin/js/jquery.backstretch.min.js"></script>
    <script type="text/javascript" src="{{app_url()}}/assets/common-js.js"></script>
    @if($module=='verify_auth_person')
        <script type="text/javascript" src="{{app_url()}}/assets/admin/js/app/auth_person_req.js"></script>
    @endif

    <script>
        $.backstretch("{{app_url()}}/assets/admin/img/stf-full-form.jpg", {pixelRatio: 2});
    </script>
	<script>
        var BASE_URL = "{{ config('customparam.APP_URL') }}";
     </script>
    <div id="page_loader">
        <img src="{{app_url()}}/assets/img/ajax-loader1.gif" alt="Loading..."/>
    </div>

  </body>
</html>
