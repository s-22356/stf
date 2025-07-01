 <!-- **********************************************************************************************************************************************************
      MAIN SIDEBAR MENU
      *********************************************************************************************************************************************************** -->
      <!--sidebar start-->
      <aside>
        <div id="sidebar"  class="nav-collapse">
            <!-- sidebar menu start-->
            <ul class="sidebar-menu" id="nav-accordion">

                <p class="centered">
                    <a href="{{app_url()}}/admin/dashboard">
                        @if(Session::get('udin_photo_base64')   !=  null)
                            <img src="data:image/jpeg;base64,{{ Session::get('udin_photo_base64') }}" class="img-circle" width="60">
                        @else
                            <img src="{{app_url()}}/assets/admin/img/fr-05.jpg" class="img-circle" width="60">
                        @endif
                    </a>
                </p>
                  <h5 class="centered">{{Session::get('name')}}</h5>

                <li class="mt">
                    <a class="<?php echo $module =='dashboard' ? 'active' :''?>" href="{{app_url()}}/admin/dashboard">    
                        <!-- {{app_url()}}/admin/dashboard -->
                        <i class="fa fa-dashboard"></i>
                        <span>Dashboard </span>
                    </a>
                </li>

             <li class="sub-menu">
                    <a class="<?php echo $module =='upload' ? 'active' :''?>" href="javascript:void(0);" >
                        <i class="fa fa-upload" aria-hidden="true"></i>
                        <span>Generate Notice</span>
                    </a>
                    <ul class="sub">
                        <li class="<?php echo $active_page =='certificate-excel' ? 'active' :''?>">
                            <a href="{{app_url()}}/admin/upload/certificate-excel">Notice Data Entry</a>
                        </li>
                        <li class="<?php echo $active_page =='udin-generate' ? 'active' :''?>">
                               <a href="{{app_url()}}/admin/udin/generate-certificate">Upload to UDIN</a>
                            </li>
                            
                           <li class="<?php echo $active_page =='udin-generated' ? 'active' :''?>">
                            <a href="{{app_url()}}/admin/udin/generated-certificate">Generated Notice List</a>
                           </li>

                           <li class="<?php echo $active_page =='download-document' ? 'active' :''?>">
                            <a href="{{app_url()}}/admin/udin/downloaded-certificate">All Generated Notice</a>
                           </li>
                      
                    </ul>
                </li>
            @if(Session::get('auth_type')   !=  "auth_user")
                <li class="sub-menu">
                       <a class="<?php echo $module =='auth-perosn' ? 'active' :''?>" href="javascript:void(0);" >
                        <i class="fa fa-book" aria-hidden="true"></i>
                            <span>Authorised Person</span>
                       </a>
                       <ul class="sub">
                            <!-- <li class="<?php echo $active_page =='auth-list' ? 'active' :''?>">
                               <a href="{{app_url()}}/admin/udin/auth-person-list">Authorised Person List</a>
                            </li> -->
                            
                           <li class="<?php echo $active_page =='add-auth-reqest' ? 'active' :''?>">
                            <a href="{{app_url()}}/admin/udin/send-auth-request">Request to be Authorised Person</a>
                           </li>
                       </ul>
                </li>
            @endif   
   
            </ul>
            <!-- sidebar menu end-->
        </div>
    </aside>
    <!--sidebar end-->
