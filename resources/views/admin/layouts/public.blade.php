<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.common.header')
</head>
<body>
    <section id="container">
        <!-- ********************************************************************************************************************************************************** TOP BAR CONTENT & NOTIFICATIONS *********************************************************************************************************************************************************** -->
        @include('admin.common.topbar')
        <!-- ********************************************************************************************************************************************************** MAIN SIDEBAR MENU *********************************************************************************************************************************************************** -->
        @include('admin.common.sidebar')
        <!-- ********************************************************************************************************************************************************** MAIN CONTENT *********************************************************************************************************************************************************** -->
        <!--main content start-->
        @yield('content')
        <!--main content end-->
        @if(function_exists('flash_message'))
            {!! flash_message() !!}
        @endif
        @include('admin.common.footer')
    </section>
    <div id="page_loader">
        <img src="{{app_url()}}/assets/img/ajax-loader1.gif" alt="Loading..."/>
    </div>
    @yield('scripts') <!-- Add this line here -->
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
