<!-- header section start -->
<div class="header_section">

    <div class="row">
        <div class="col-md-2">
                <div class="logo"><a href="{{app_url()}}"><img  src="{{app_url()}}/assets/user/images/logo-2.png"></a></div>
         </div>

        <div class="col-md-8">
          <h2 class="text-center ">    Cyber Security Centre of Excellence </h2>

           <h4 class="text-center"> Department of Information Technology & Electronics, Government of West Bengal </h4>
        </div>

        <div class="col-md-2">
            <div class="logo-2"><a href="{{app_url()}}"><img  src="{{app_url()}}/assets/user/images/cscoe-logo.png"></a></div>
        </div>

    </div>
</div>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    {{-- <div class="logo"><a href="index.html"><img  src="/assets/user/images/logo-2.png"></a></div> --}}
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
        <li class="nav-item">
        <a class="nav-link" href="{{app_url()}}">HOME</a>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="{{app_url()}}">Give Feedback </a>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="{{env('APP_URL')}}/download_cert">Download Certificate</a>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="{{app_url()}}">Contact us</a>
        </li>
    {{--  <li class="nav-item">
        <a class="nav-link" href="remot.html">REMOT CONTROL</a>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="contact.html">CONTACT US</a>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="#"><img src="{{app_url()}}/assets/user/images/search-icon.png"></a>
        </li>
        <li class="nav-item active">
        <a class="nav-link" href="#">SIGN IN</a>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="#">REGISTER</a>
        </li> --}}
    </ul>
    </div>
</nav>
<!-- header section end -->
