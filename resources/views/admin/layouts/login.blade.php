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

  </head>

  <body>

    @if(Session::has('expired_message'))
        <br><div class="container">
            <div class="alert alert-danger">
                <strong>Error!</strong> {{ Session::get('expired_message') }}
            </div>
        </div>
    @endif
      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->

        @yield('content')
    <!-- js placed at the end of the document so the pages load faster -->

    <div id="page_loader">
        <img src="{{app_url()}}/assets/img/ajax-loader1.gif" alt="Loading..."/>
    </div>

    <script src="{{app_url()}}/assets/admin/js/jquery.js"></script>
    <script src="{{app_url()}}/assets/admin/js/bootstrap.min.js"></script>

    <!--BACKSTRETCH-->
    <!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
    <script type="text/javascript" src="{{app_url()}}/assets/admin/js/jquery.backstretch.min.js"></script>
    <script type="text/javascript" src="{{app_url()}}/assets/common-js.js"></script>
    @if($module=='login')
        <script type="text/javascript" src="{{app_url()}}/assets/admin/js/app/login.js"></script>
    @endif

    <script>
        $.backstretch("{{app_url()}}/assets/admin/img/stf-full-form.jpg", {pixelRatio: 2});
    </script>
	<script>
        var BASE_URL = "{{ config('customparam.APP_URL') }}";
     </script>


  </body>
</html>
<style>
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
